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
        Schema::table('shifts', function (Blueprint $table) {
            $table->dropColumn('time_of_day');
        });
    }

    public function down()
    {
        Schema::table('shifts', function (Blueprint $table) {
            $table->string('time_of_day')->nullable();
        });
    }
};
