<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['client_id','name', 'is_charter', 'payment_type', 'retail_price', 'driver_price','estimated_overtime_hours','overtime_hourly_wage', 'registration_location'];

    public function payments()
    {
        return $this->hasMany(ProjectEmployeePayment::class);
    }

    public function allowanceByPorjects()
    {
        return $this->hasMany(AllowanceByProject::class);
    }

    public function holiday()
    {
        return $this->hasOne(ProjectHoliday::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

}
