<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectAllowance extends Model
{
    use HasFactory;

    protected $table = 'project_allowances'; // テーブル名を指定
    protected $fillable = ['project_id', 'name', 'retail_amount', 'driver_amount', 'is_required'];

    // プロジェクトとのリレーション
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function shiftAllowance()
    {
        return $this->belongsToMany(ShiftProjectVehicle::class, 'shift_project_vehicle_allowances');
    }
}
