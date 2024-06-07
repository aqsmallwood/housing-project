{@extends('layouts.app')

@section('content')

<h1>Patients</h1>

<div class="row">
    <div class="col-md-6">
        <form action="{{ route('patients.index') }}" method="GET" class="form-inline">
            <div class="form-group">
                <input type="text" name="search" class="form-control" placeholder="Search patients..."
                       value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn btn-primary ml-2">Search</button>
        </form>
    </div>
    <div class="col-md-6 text-right">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addPatientModal">
            Add Patient
        </button>
    </div>
</div>

<hr>

<div class="modal fade" id="addPatientModal" tabindex="-1" role="dialog" aria-labelledby="addPatientModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPatientModalLabel">Add Patient</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('patients.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" name="first_name" id="first_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="middle_name">Middle Name</label>
                        <input type="text" name="middle_name" id="middle_name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" name="last_name" id="last_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth</label>
                        <input type="date" name="dob" id="dob" class="form-control">
                    </div>

                    <h5>Kids</h5>
                    <div id="kidsSection">
                        <div class="form-group">
                            <label for="kid_name_0">Name</label>
                            <input type="text" name="kid_names[]" id="kid_name_0" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="kid_dob_0">Date of Birth</label>
                            <input type="date" name="kid_dobs[]" id="kid_dob_0" class="form-control">
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" id="addKidButton">Add Kid</button>

                    <h5>Adults</h5>
                    <div id="adultsSection">
                        <div class="form-group">
                            <label for="adult_first_name_0">First Name</label>
                            <input type="text" name="adult_first_names[]" id="adult_first_name_0" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="adult_middle_name_0">Middle Name</label>
                            <input type="text" name="adult_middle_names[]" id="adult_middle_name_0"
                                   class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="adult_last_name_0">Last Name</label>
                            <input type="text" name="adult_last_names[]" id="adult_last_name_0" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="adult_dob_0">Date of Birth</label>
                            <input type="date" name="adult_dobs[]" id="adult_dob_0" class="form-control">
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" id="addAdultButton">Add Adult</button>
                    <br>
                    <button type="submit" class="btn btn-primary">Add Patient</button>
                </form>
            </div>
        </div>
    </div>
</div>

<table class="table">
    <thead>
    <tr>
        <th>Name</th>
        <th>Status</th>
        <th>Room</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($patients as $patient)
    <tr>
        <td>
            <a href="#" data-bs-toggle="modal" data-bs-target="#patientModal{{ $patient->id }}">
                {{ $patient->first_name }} {{ $patient->last_name }}
            </a>
        </td>
        <td>{{ $patient->status }}</td>
        <td>
            @if ($patient->currentRoom)
            {{ $patient->currentRoom->room_number }}
            @else
            N/A
            @endif
        </td>
        <td>
            @if ($patient->status === 'resident')
            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#dischargeModal{{ $patient->id }}">
                Discharge
            </button>
            @else
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#intakeModal{{ $patient->id }}">
                Intake
            </button>
            @endif
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editPatientModal{{ $patient->id }}">
                Edit
            </button>
        </td>
    </tr>

    <!-- Patient Summary Modal -->
    <div class="modal fade" id="patientModal{{ $patient->id }}" tabindex="-1" role="dialog"
         aria-labelledby="patientModalLabel{{ $patient->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="patientModalLabel{{ $patient->id }}">Patient Summary</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Name:</strong> {{ $patient->first_name }} {{ $patient->middle_name }} {{
                        $patient->last_name }}</p>
                    <p><strong>Status:</strong> {{ $patient->status }}</p>

                    <p><strong>Room:</strong>
                        @if ($patient->currentRoom)
                        {{ $patient->currentRoom->room_number }}
                        @else
                        N/A
                        @endif
                    </p>

                    <h5>Kids</h5>
                    @if ($patient->kids->count() > 0)
                    <ul>
                        @foreach ($patient->kids as $kid)
                        <li>
                            <strong>Name:</strong> {{ $kid->name }}<br>
                            <strong>Date of Birth:</strong> {{ $kid->dob }} ({{ $kid->age }} years)
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p>No kids found.</p>
                    @endif

                    <h5>Adults</h5>
                    @if ($patient->adults->count() > 0)
                    <ul>
                        @foreach ($patient->adults as $adult)
                        <li>
                            <strong>First Name:</strong> {{ $adult->first_name }}<br>
                            <strong>Middle Name:</strong> {{ $adult->middle_name }}<br>
                            <strong>Last Name:</strong> {{ $adult->last_name }}<br>
                            <strong>Date of Birth:</strong> {{ $adult->dob }}
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p>No adults found.</p>
                    @endif

                    <h5>Moves</h5>
                    @foreach ($patient->moves as $move)
                    <p>Room: {{ $move->room->room_number }} - {{ $move->type }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Intake Modal -->
    <div class="modal fade" id="intakeModal{{ $patient->id }}" tabindex="-1" role="dialog"
         aria-labelledby="intakeModalLabel{{ $patient->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="intakeModalLabel{{ $patient->id }}">Intake Patient</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('patients.intake', $patient) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="room_id">Available Rooms</label>
                            <select name="room_id" id="room_id" class="form-control" required>
                                @foreach ($availableRooms as $room)
                                <option value="{{ $room->id }}">{{ $room->room_number }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Intake Patient</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Discharge Modal -->
    <div class="modal fade" id="dischargeModal{{ $patient->id }}" tabindex="-1" role="dialog"
         aria-labelledby="dischargeModalLabel{{ $patient->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dischargeModalLabel{{ $patient->id }}">Discharge Patient</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to discharge this patient?</p>
                    <form action="{{ route('patients.discharge', $patient) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Discharge Patient</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Patient Modal -->
    <div class="modal fade" id="editPatientModal{{ $patient->id }}" tabindex="-1" role="dialog"
         aria-labelledby="editPatientModalLabel{{ $patient->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPatientModalLabel{{ $patient->id }}">Edit Patient</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editPatientForm{{ $patient->id }}" action="{{ route('patients.update', $patient) }}"
                          method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" name="first_name" id="first_name" class="form-control"
                                   value="{{ $patient->first_name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="middle_name">Middle Name</label>
                            <input type="text" name="middle_name" id="middle_name" class="form-control"
                                   value="{{ $patient->middle_name }}">
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" name="last_name" id="last_name" class="form-control"
                                   value="{{ $patient->last_name }}" required>
                        </div>

                        <h5>Kids</h5>
                        <div id="editKidsSection{{ $patient->id }}">
                            @foreach ($patient->kids as $index => $kid)
                            <div class="form-group">
                                <input type="hidden" name="kid_ids[]" value="{{ $kid->id }}">
                                <label for="kid_name_{{ $patient->id }}_{{ $index }}">Name</label>
                                <input type="text" name="kid_names[]" id="kid_name_{{ $patient->id }}_{{ $index }}"
                                       class="form-control" value="{{ $kid->name }}">
                            </div>
                            <div class="form-group">
                                <label for="kid_dob_{{ $patient->id }}_{{ $index }}">Date of Birth</label>
                                <input type="date" name="kid_dobs[]" id="kid_dob_{{ $patient->id }}_{{ $index }}"
                                       class="form-control" value="{{ $kid->dob }}">
                            </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-primary" id="addEditKidButton{{ $patient->id }}">Add Kid
                        </button>

                        <h5>Adults</h5>
                        <div id="editAdultsSection{{ $patient->id }}">
                            @foreach ($patient->adults as $index => $adult)
                            <div class="form-group">
                                <input type="hidden" name="adult_ids[]" value="{{ $adult->id }}">
                                <label for="adult_first_name_{{ $patient->id }}_{{ $index }}">Name</label>
                                <input type="text" name="adult_first_names[]"
                                       id="adult_first_name_{{ $patient->id }}_{{ $index }}" class="form-control"
                                       value="{{ $adult->first_name }}">
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="adult_ids[]" value="{{ $adult->id }}">
                                <label for="adult_middle_name_{{ $patient->id }}_{{ $index }}">Name</label>
                                <input type="text" name="adult_middle_names[]"
                                       id="adult_middle_name_{{ $patient->id }}_{{ $index }}" class="form-control"
                                       value="{{ $adult->middle_name }}">
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="adult_ids[]" value="{{ $adult->id }}">
                                <label for="adult_last_name_{{ $patient->id }}_{{ $index }}">Name</label>
                                <input type="text" name="adult_last_names[]"
                                       id="adult_last_name_{{ $patient->id }}_{{ $index }}" class="form-control"
                                       value="{{ $adult->last_name }}">
                            </div>
                            <div class="form-group">
                                <label for="adult_dob_{{ $patient->id }}_{{ $index }}">Date of Birth</label>
                                <input type="date" name="adult_dobs[]" id="adult_dob_{{ $patient->id }}_{{ $index }}"
                                       class="form-control" value="{{ $adult->dob }}">
                            </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-primary" id="addEditAdultButton{{ $patient->id }}">Add
                            Adult
                        </button>
                        <br>
                        <button type="submit" class="btn btn-primary">Update Patient</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    </tbody>
</table>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        var kidCount = 1;
        var adultCount = 1;

        $('#addKidButton').click(function () {
            var kidSection = `
                    <div class="form-group">
                        <label for="kid_name_${kidCount}">Name</label>
                        <input type="text" name="kid_names[]" id="kid_name_${kidCount}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="kid_dob_${kidCount}">Date of Birth</label>
                        <input type="date" name="kid_dobs[]" id="kid_dob_${kidCount}" class="form-control">
                    </div>
                `;
            $('#kidsSection').append(kidSection);
            kidCount++;
        });

        $('#addAdultButton').click(function () {
            var adultSection = `
                    <div class="form-group">
                        <label for="adult_first_name_${adultCount}">First Name</label>
                        <input type="text" name="adult_first_names[]" id="adult_first_name_${adultCount}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="adult_middle_name_${adultCount}">Middle Name</label>
                        <input type="text" name="adult_middle_names[]" id="adult_middle_name_${adultCount}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="adult_last_name_${adultCount}">Last Name</label>
                        <input type="text" name="adult_last_names[]" id="adult_last_name_${adultCount}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="adult_dob_${adultCount}">Date of Birth</label>
                        <input type="date" name="adult_dobs[]" id="adult_dob_${adultCount}" class="form-control">
                    </div>
                `;
            $('#adultsSection').append(adultSection);
            adultCount++;
        });
    });
</script>
<script>
    $(document).ready(function () {
        var editKidCount = {
        {
            $patient->kids_count
        }
    }
        ;
        var editAdultCount = {
        {
            $patient->adults_count
        }
    }
        ;

        $('#addEditKidButton{{ $patient->id }}').click(function () {
            var kidSection = `
                    <input type="hidden" name="kid_ids[]" value="">
                    <div class="form-group">
                        <label for="kid_name_{{ $patient->id }}_${editKidCount}">Name</label>
                        <input type="text" name="kid_names[]" id="kid_name_{{ $patient->id }}_${editKidCount}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="kid_dob_{{ $patient->id }}_${editKidCount}">Date of Birth</label>
                        <input type="date" name="kid_dobs[]" id="kid_dob_{{ $patient->id }}_${editKidCount}" class="form-control">
                    </div>
                `;
            $('#editKidsSection{{ $patient->id }}').append(kidSection);
            editKidCount++;
        });

        $('#addEditAdultButton{{ $patient->id }}').click(function () {
            var adultSection = `
                    <input type="hidden" name="adult_ids[]" value="">
                    <div class="form-group">
                        <label for="adult_first_name_{{ $patient->id }}_${editAdultCount}">Name</label>
                        <input type="text" name="adult_first_names[]" id="adult_first_name_{{ $patient->id }}_${editAdultCount}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="adult_middle_name_{{ $patient->id }}_${editAdultCount}">Name</label>
                        <input type="text" name="adult_middle_names[]" id="adult_middle_name_{{ $patient->id }}_${editAdultCount}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="adult_last_name_{{ $patient->id }}_${editAdultCount}">Name</label>
                        <input type="text" name="adult_last_names[]" id="adult_last_name_{{ $patient->id }}_${editAdultCount}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="adult_dob_{{ $patient->id }}_${editAdultCount}">Date of Birth</label>
                        <input type="date" name="adult_dobs[]" id="adult_dob_{{ $patient->id }}_${editAdultCount}" class="form-control">
                    </div>
                `;
            $('#editAdultsSection{{ $patient->id }}').append(adultSection);
            editAdultCount++;
        });
    });
</script>
@endsection
}
