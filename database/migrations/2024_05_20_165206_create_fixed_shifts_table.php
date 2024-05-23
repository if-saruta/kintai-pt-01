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
        Schema::create('fixed_shifts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('employee_id');
            $table->string('day'); // 曜日 ('monday', 'tuesday', etc.)
            $table->string('week'); // 週の指定 ('every', 'first', 'second', 'third', 'fourth', 'fifth', 'bi-weekly-odd', 'bi-weekly-even')
            $table->boolean('active_on_holidays')->default(false); // 祝日に稼働するかどうか
            $table->timestamps();

            // 外部キー制約
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixed_shifts');
    }
};
