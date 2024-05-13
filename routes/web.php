<?php

use App\Http\Controllers\PatientController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
//Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
//Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
//Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');
//Route::get('/rooms/{room}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
//Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
//Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->name('rooms.destroy');

Route::resource('rooms', RoomController::class);
Route::resource('patients', PatientController::class);
Route::post('/patients/{patient}/intake', [PatientController::class, 'intake'])->name('patients.intake');
Route::post('/patients/{patient}/discharge', [PatientController::class, 'discharge'])->name('patients.discharge');
