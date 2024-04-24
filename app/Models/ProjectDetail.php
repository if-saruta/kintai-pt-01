<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDetail extends Model
{
    use HasFactory;

    protected $table = 'project_details';

    protected $fillable = [
        'project_id',
        'delivery_type',
        'arrival_location',
        'delivery_area',
        'delivery_address',
        'cargo_type',
        'arrival_time',
        'finish_time',
        'count',
        'operation_date',
        'vehicle',
        'cash_on_delivery',
        'retail_price_for_hgl',
        'notes'
    ];

    // プロジェクトに属するリレーション
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
