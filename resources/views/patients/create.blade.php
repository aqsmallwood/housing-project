@extends('layouts.app')

@section('content')
<h1>Create Patient</h1>

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
        <label for="status">Status</label>
        <select name="status" id="status" class="form-control" required>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Create Patient</button>
</form>
@endsection
