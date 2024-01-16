<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllowanceByOther extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'allowanceName',
        'amount',
    ];
}
