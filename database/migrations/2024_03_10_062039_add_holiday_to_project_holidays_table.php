<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_holidays', function (Blueprint $table) {
            $table->boolean('public_holiday')->default(false)->after('saturday');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_holidays', function (Blueprint $table) {
            $table->dropColumn('public_holiday');
        });
    }
};
