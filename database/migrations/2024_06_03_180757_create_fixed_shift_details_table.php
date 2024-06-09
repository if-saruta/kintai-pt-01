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
        Schema::create('fixed_shift_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fixed_shift_id')->constrained()->onDelete('cascade');
            $table->integer('week_number'); // 1: 一週目
            $table->integer('day_of_week'); // 0: 月曜日,,,6: 日曜日
            $table->string('time_of_day'); // 0: am 1: pm
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixed_shift_details');
    }
};
