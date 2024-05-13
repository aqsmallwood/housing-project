@extends('layouts.app')

@section('content')
<h1>Room Details</h1>

<div>
    <strong>Room Number:</strong> {{ $room->room_number }}
</div>
<div>
    <strong>Status:</strong> {{ $room->status }}
</div>

<a href="{{ route('rooms.edit', $room) }}" class="btn btn-primary">Edit</a>

<form action="{{ route('rooms.destroy', $room) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this room?')">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger">Delete</button>
</form>

<a href="{{ route('rooms.index') }}" class="btn btn-primary">Back to Rooms</a>
@endsection
