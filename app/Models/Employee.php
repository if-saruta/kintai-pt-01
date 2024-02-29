<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['register_number', 'name', 'initials', 'post_code', 'address', 'employment_status', 'company_id', 'is_invoice', 'vehicle_rental_type', 'vehicle_id'];

    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }

    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class);
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
