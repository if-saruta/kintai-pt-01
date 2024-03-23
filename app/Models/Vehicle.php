<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['place_name','class_number','hiragana','number','company_id'];

    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
