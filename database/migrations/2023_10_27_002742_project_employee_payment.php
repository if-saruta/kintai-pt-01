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
        Schema::create('project_employee_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('project_id');
            $table->decimal('amount', 15, 2)->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('project_id')->references('id')->on('projects');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_employee_payments');
    }
};
