@extends('layouts.app')

@section('content')
<h1>Create Room</h1>

<form action="{{ route('rooms.store') }}" method="POST">
    @csrf

    <div class="form-group">
        <label for="room_number">Room Number</label>
        <input type="text" name="room_number" id="room_number" class="form-control" required>
        @error('room_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="status">Status</label>
        <select name="status" id="status" class="form-control" required>
            <option value="vacant">Vacant</option>
            <option value="occupied">Occupied</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Create Room</button>
</form>

<a href="{{ route('rooms.index') }}">Back to Rooms</a>
@endsection
