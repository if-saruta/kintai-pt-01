<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['clientName','clientNameByPDF','name', 'payment_type', 'retail_price', 'driver_price','estimated_overtime_hours','overtime_hourly_wage'];

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

    // // public function shifts()
    // // {
    // //     return $this->hasMany(Shift::class);
    // // }
    // public function shifts()
    // {
    //     return $this->belongsToMany(Shift::class, 'shift_projects');
    // }

}
