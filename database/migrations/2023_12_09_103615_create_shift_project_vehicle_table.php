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
        Schema::create('shift_project_vehicle', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shift_id');
            $table->unsignedBigInteger('project_id')->nullable();//案件名
            $table->string('unregistered_project')->nullable();//未登録案件名
            $table->string('charter_project_name')->nullable();
            $table->unsignedBigInteger('vehicle_id')->nullable();//車両
            $table->string('unregistered_vehicle')->nullable();//未登録車両
            $table->unsignedInteger('retail_price')->nullable();//上代
            $table->unsignedInteger('driver_price')->nullable();//給与
            $table->unsignedInteger('total_allowance')->nullable();//手当合計
            $table->unsignedInteger('parking_fee')->nullable();//パーキング代
            $table->unsignedInteger('expressway_fee')->nullable();//高速代
            $table->unsignedInteger('overtime_fee')->nullable();//残業代
            $table->string('vehicle_rental_type')->nullable();// 0 : 自車 1 : 月リース 2 : なんでも月リース 3 : 日割り
            $table->unsignedBigInteger('rental_vehicle_id')->nullable();//車両
            $table->string('time_of_day')->nullable();
            $table->timestamps();

            $table->foreign('shift_id')->references('id')->on('shifts')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
            $table->foreign('rental_vehicle_id')->references('id')->on('vehicles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_project_vehicle');
    }
};
