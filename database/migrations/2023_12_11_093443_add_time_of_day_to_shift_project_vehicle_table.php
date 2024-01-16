<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('shift_project_vehicle', function (Blueprint $table) {
            $table->string('time_of_day')->nullable(); // 例として、文字列型のカラムを追加
        });
    }

    public function down()
    {
        Schema::table('shift_project_vehicle', function (Blueprint $table) {
            $table->dropColumn('time_of_day');
        });
    }
};
