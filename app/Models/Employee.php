<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'employment_status', 'company_id', 'vehicle_rental_type', 'vehicle_id'];

    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }


    public function payments()
    {
        return $this->hasMany(ProjectEmployeePayment::class);
    }

    public function allowanceByPorjects()
    {
        return $this->hasMany(AllowanceByProject::class);
    }

    public function allowanceByOthers()
    {
        return $this->hasMany(AllowanceByOther::class);
    }
}
