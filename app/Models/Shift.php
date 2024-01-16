<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = ['date','employee_id','unregistered_employee', 'time_of_day'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function projectsVehicles()
    {
        return $this->hasMany(ShiftProjectVehicle::class);
    }

}
