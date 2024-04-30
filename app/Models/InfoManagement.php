<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoManagement extends Model
{
    use HasFactory;

    protected $table = 'info_management';

    // 一括代入可能な属性
    protected $fillable = [
        'tax_rate', 'discount_rate', 'monthly_lease_fee', 'monthly_lease_insurance_fee',
        'monthly_lease_second_fee', 'monthly_lease_second_insurance_fee', 'prorated_lease_fee',
        'prorated_insurance_fee', 'admin_commission_rate', 'admin_fee_switch',
        'max_admin_fee', 'min_admin_fee', 'transfer_fee'
    ];
}
