<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllowanceByProject extends Model
{
    use HasFactory;

    protected $table = 'allowance_by_projects';
    protected $fillable = [
        'employee_id',
        'project_id',
        'allowanceName',
        'amount',
    ];
}
