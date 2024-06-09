<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FixedShiftDetail extends Model
{
    use HasFactory;

    protected $fillable = ['fixed_shift_id', 'week_number', 'day_of_week', 'time_of_day'];

    public function fixedShift()
    {
        return $this->belongsTo(FixedShift::class);
    }
}
