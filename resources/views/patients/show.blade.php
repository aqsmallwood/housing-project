@extends('layouts.app')

@section('content')
<h1>Patient Details</h1>

<div>
    <strong>First Name:</strong> {{ $patient->first_name }}
</div>
<div>
    <strong>Middle Name:</strong> {{ $patient->middle_name }}
</div>
<div>
    <strong>Last Name:</strong> {{ $patient->last_name }}
</div>
<div>
    <strong>Status:</strong> {{ $patient->status }}
</div>

<a href="{{ route('patients.edit', $patient) }}" class="btn btn-primary">Edit</a>

<form action="{{ route('patients.destroy', $patient) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this patient?')">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger">Delete</button>
</form>

<a href="{{ route('patients.index') }}" class="btn btn-secondary">Back to Patients</a>
@endsection
