<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kid extends Model
{
    protected $fillable = ['name', 'dob'];

    use HasFactory;

    private function getAge()
    {
        if ($this->dob) {
            $age = Carbon::parse($this->dob)->diffInYears(Carbon::now());
            return round($age);
        }

        return null;
    }

    public function age(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getAge(),
        );

    }
}
