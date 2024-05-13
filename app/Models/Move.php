<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Move extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id', 'room_id', 'moved_at', 'type'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
