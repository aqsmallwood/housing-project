@extends('layouts.app')

@section('content')
<h1>Edit Patient</h1>

<form action="{{ route('patients.update', $patient) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="first_name">First Name</label>
        <input type="text" name="first_name" id="first_name" class="form-control" value="{{ $patient->first_name }}" required>
    </div>

    <div class="form-group">
        <label for="middle_name">Middle Name</label>
        <input type="text" name="middle_name" id="middle_name" class="form-control" value="{{ $patient->middle_name }}">
    </div>

    <div class="form-group">
        <label for="last_name">Last Name</label>
        <input type="text" name="last_name" id="last_name" class="form-control" value="{{ $patient->last_name }}" required>
    </div>

    <div class="form-group">
        <label for="status">Status</label>
        <select name="status" id="status" class="form-control" required>
            <option value="active" {{ $patient->status === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ $patient->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Update Patient</button>
</form>
@endsection
