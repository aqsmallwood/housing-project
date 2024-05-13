@extends('layouts.app')

@section('content')
<h1>Edit Room</h1>

<form action="{{ route('rooms.update', $room) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="room_number">Room Number</label>
        <input type="text" name="room_number" id="room_number" class="form-control" value="{{ $room->room_number }}" required>
        @error('room_number')
           <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="status">Status</label>
        <select name="status" id="status" class="form-control" required>
            <option value="vacant" {{ $room->status === 'vacant' ? 'selected' : '' }}>Vacant</option>
            <option value="occupied" {{ $room->status === 'occupied' ? 'selected' : '' }}>Occupied</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Update Room</button>
</form>
@endsection
