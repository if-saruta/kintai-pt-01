<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftProjectVehicleAllowance extends Model
{
    use HasFactory;

    protected $fillable = ['shift_project_vehicle_id', 'project_allowance_id'];

    public function shiftProjectVehicle()
    {
        return $this->belongsTo(ShiftProjectVehicle::class);
    }

    public function projectAllowance()
    {
        return $this->belongsTo(ProjectAllowance::class);
    }
}
