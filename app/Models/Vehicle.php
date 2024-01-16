<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = ['number','company_id'];

    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
