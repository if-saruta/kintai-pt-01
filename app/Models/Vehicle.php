<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'ownership_type',
        'ownership_id',
        'employee_id',
        'vehicle_type',
        'category',
        'brand_name',
        'model',
        'inspection_expiration_date',
        'place_name',
        'class_number',
        'hiragana',
        'number'
    ];

    protected $casts = [
        'inspection_expiration_date' => 'datetime',
    ];

    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function ownership()
    {
        return $this->morphTo();
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
