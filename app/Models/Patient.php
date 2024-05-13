<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function Laravel\Prompts\error;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = ['first_name', 'middle_name', 'last_name', 'status', 'dob'];

    public function adults()
    {
        return $this->hasMany(Adult::class);
    }

    public function kids()
    {
        return $this->hasMany(Kid::class);
    }

    public function moves()
    {
        return $this->hasMany(Move::class);
    }

    private function getCurrentRoom() {
        $lastIntake = $this->moves()->where('type', 'intake')->orderBy('moved_at', 'desc')->first();
        $lastDischarge = $this->moves()->where('type', 'discharge')->orderBy('moved_at', 'desc')->first();

        if ($this->status == 'resident' && $lastIntake && (!$lastDischarge || $lastIntake->date >= $lastDischarge->date)) {
            error_log($lastIntake);
            return $lastIntake->room;
        }

        return null;
    }

    public function currentRoom(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getCurrentRoom(),
        );

    }

}
