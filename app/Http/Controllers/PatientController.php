<?php

namespace App\Http\Controllers;

use App\Models\Adult;
use App\Models\Kid;
use App\Models\Move;
use App\Models\Patient;
use App\Models\Room;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->input('search');

        $patients = Patient::query()
            ->when($searchTerm, function ($query, $searchTerm) {
                $query->where(function ($query) use ($searchTerm) {
                    $query->whereRaw('LOWER(first_name) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
                        ->orWhereRaw('LOWER(middle_name) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
                        ->orWhereRaw('LOWER(last_name) LIKE ?', ['%' . strtolower($searchTerm) . '%']);
                });
            })
            ->withCount(['kids', 'adults'])
            ->get();
        $availableRooms = Room::where('status', 'vacant')->get();

//        foreach ($patients as $patient) {
//            $lastIntake = $patient->moves()->where('type', 'intake')->latest()->first();
//            $lastDischarge = $patient->moves()->where('type', 'discharge')->latest()->first();
//
//            if ($lastIntake && (!$lastDischarge || $lastIntake->date > $lastDischarge->date)) {
//                $patient->room = $lastIntake->room;
//            } else {
//                $patient->room = null;
//            }
//        }

        return view('patients.index', compact('patients', 'availableRooms'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'middle_name' => 'nullable',
        ]);

        $patient = Patient::create(array_merge($validatedData, ['status' => 'new']));

        $kidNames = $request->input('kid_names', []);
        $kidDobs = $request->input('kid_dobs', []);
        foreach ($kidNames as $index => $kidName) {
            if (!empty($kidName)) {
                $validatedKidData = $request->validate([
                    'kid_dobs.' . $index => 'nullable|date|before_or_equal:today',
                ]);

                $patient->kids()->create([
                    'name' => $kidName,
                    'dob' => $validatedKidData['kid_dobs'][$index] ?? null,
                ]);
            }
        }

        $adultFirstNames = $request->input('adult_first_names', []);
        $adultMiddleNames = $request->input('adult_middle_names', []);
        $adultLastNames = $request->input('adult_last_names', []);
        $adultDobs = $request->input('adult_dobs', []);
        foreach ($adultFirstNames as $index => $adultFirstName) {
            if (!empty($adultFirstName)) {
                $validatedAdultData = $request->validate([
                    'adult_dobs.' . $index => 'nullable|date|before_or_equal:today',
                ]);

                $patient->adults()->create([
                    'first_name' => $adultFirstName,
                    'middle_name' => $adultMiddleNames[$index] ?? null,
                    'last_name' => $adultLastNames[$index] ?? null,
                    'dob' => $validatedAdultData['adult_dobs'][$index] ?? null,
                ]);
            }
        }


        return redirect()->route('patients.index')->with('success', 'Patient created successfully');
    }

    public function show(Patient $patient)
    {
        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'middle_name' => 'nullable',
            'status' => 'required',
        ]);

        $patient->update($validatedData);

        $kidIds = $request->input('kid_ids', []);
        $kidNames = $request->input('kid_names', []);
        $kidDobs = $request->input('kid_dobs', []);
        foreach ($kidIds as $index => $kidId) {
            if (!empty($kidNames[$index])) {
                $validatedKidData = $request->validate([
                    'kid_dobs.' . $index => 'nullable|date|before_or_equal:today',
                ]);

                if ($kidId) {
                    $kid = Kid::findOrFail($kidId);
                    $kid->update([
                        'name' => $kidNames[$index],
                        'dob' => $validatedKidData['kid_dobs'][$index] ?? null,
                    ]);
                } else {
                    $patient->kids()->create([
                        'name' => $kidNames[$index],
                        'dob' => $validatedKidData['kid_dobs'][$index] ?? null,
                    ]);
                }
            } elseif ($kidId) {
                Kid::destroy($kidId);
            }
        }

        $adultIds = $request->input('adult_ids', []);
        $adultFirstNames = $request->input('adult_first_names', []);
        $adultMiddleNames = $request->input('adult_middle_names', []);
        $adultLastNames = $request->input('adult_last_names', []);
        $adultDobs = $request->input('adult_dobs', []);
        foreach ($adultIds as $index => $adultId) {
            if (!empty($adultNames[$index])) {
                $validatedAdultData = $request->validate([
                    'adult_dobs.' . $index => 'nullable|date|before_or_equal:today',
                ]);

                if ($adultId) {
                    $adult = Adult::findOrFail($adultId);
                    $adult->update([
                        'first_name' => $adultFirstNames[$index],
                        'middle_name' => $adultMiddleNames[$index],
                        'last_name' => $adultLastNames[$index],
                        'dob' => $validatedAdultData['adult_dobs'][$index] ?? null,
                    ]);
                } else {
                    $patient->adults()->create([
                        'name' => $adultNames[$index],
                        'dob' => $validatedAdultData['adult_dobs'][$index] ?? null,
                    ]);
                }
            } elseif ($adultId) {
                Adult::destroy($adultId);
            }
        }

        return redirect()->route('patients.index')->with('success', 'Patient updated successfully');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('patients.index')->with('success', 'Patient deleted successfully');
    }

    public function intake(Request $request, Patient $patient)
    {
        $validatedData = $request->validate([
            'room_id' => 'required|exists:rooms,id',
        ]);

        $room = Room::findOrFail($validatedData['room_id']);
        $room->status = 'occupied';
        $room->save();

        $patient->status = 'resident';
        $patient->save();

        // Create a new move record for the intake
        $move = new Move([
            'patient_id' => $patient->id,
            'room_id' => $room->id,
            'moved_at' => now(),
            'type' => 'intake',
        ]);
        $move->save();

        return redirect()->route('patients.index')->with('success', 'Patient intake successful');
    }

    public function discharge(Patient $patient)
    {
        $latestMove = $patient->moves()->latest()->first();
        $room = $latestMove->room;
        $room->status = 'vacant';
        $room->save();

        $patient->status = 'discharged';
        $patient->save();

        // Create a new move record for the discharge
        $move = new Move([
            'patient_id' => $patient->id,
            'room_id' => $room->id,
            'moved_at' => now(),
            'type' => 'discharge',
        ]);
        $move->save();

        return redirect()->route('patients.index')->with('success', 'Patient discharged successfully');
    }
}
