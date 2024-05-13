<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Room::create([
            'room_number' => '101',
            'status' => 'vacant',
        ]);

        Room::create([
            'room_number' => '102',
            'status' => 'occupied',
        ]);

    }
}
