<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('moves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('room_id');
            $table->dateTime('moved_at');
            $table->string('type'); // intake or discharge
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('patients');
            $table->foreign('room_id')->references('id')->on('rooms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moves');
    }
};
