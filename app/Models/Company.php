<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ['register_number','name', 'post_code', 'address', 'phone', 'fax', 'bank_name', 'account_holder_name'];
}
