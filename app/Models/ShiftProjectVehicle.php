<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftProjectVehicle extends Model
{
    use HasFactory;

    protected $table = 'shift_project_vehicle';
    protected $fillable = [
        'shift_id',
        'project_id',
        'unregistered_project',
        'vehicle_id',
        'unregistered_vehicle',
        'retail_price',
        'driver_price',
        'total_allowance',
        'vehicle_rental_type',
        'rental_vehicle_id',
        'time_of_day',
    ];

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    // Vehicle モデルへのリレーション (レンタル車両)
    public function rentalVehicle()
    {
        return $this->belongsTo(Vehicle::class, 'rental_vehicle_id');
    }
}
