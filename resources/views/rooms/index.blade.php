@extends('layouts.app')

@section('content')
<h1>Rooms</h1>

@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif


<a href="{{ route('rooms.create') }}" class="btn btn-primary">Create Room</a>
@if ($rooms->count() > 0)
<ul>
    @foreach ($rooms as $room)
    <li>
        <a href="{{ route('rooms.show', $room) }}">{{ $room->room_number }}</a>
    </li>
    @endforeach
</ul>
@else
<p>No rooms found.</p>
@endif
@endsection
