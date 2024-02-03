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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients');
            $table->boolean('is_charter')->default(false);
            $table->string('name')->nullable();
            $table->string('payment_type')->nullable();  //0 歩合 1 日給
            $table->decimal('retail_price', 10, 2)->nullable();
            $table->decimal('driver_price', 10, 2)->nullable();
            $table->decimal('estimated_overtime_hours')->nullable();
            $table->decimal('overtime_hourly_wage')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
