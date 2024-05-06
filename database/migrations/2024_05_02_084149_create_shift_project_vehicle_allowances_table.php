<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shift_project_vehicle_allowances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shift_project_vehicle_id');
            $table->unsignedBigInteger('project_allowance_id');
            $table->timestamps();

            // 外部キー制約の名前を短くする
            $table->foreign('shift_project_vehicle_id', 'spv_id_foreign')->references('id')->on('shift_project_vehicle')->onDelete('cascade');
            $table->foreign('project_allowance_id', 'pa_id_foreign')->references('id')->on('project_allowances')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_project_vehicle_allowances');
    }
};
