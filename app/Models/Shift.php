<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{

    protected $fillable = ['date','employee_id','unregistered_employee', 'time_of_day'];

    // dateカラムをキャスト
    // protected $casts = [
    //     'date' => 'datetime',
    // ];

    public function employee()
    {
        return $this->belongsTo(Employee::class)->withTrashed();
    }

    public function projectsVehicles()
    {
        return $this->hasMany(ShiftProjectVehicle::class);
    }

}
