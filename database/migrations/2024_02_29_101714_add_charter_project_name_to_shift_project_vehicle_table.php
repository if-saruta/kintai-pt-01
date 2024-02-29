<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('shift_project_vehicle', function (Blueprint $table) {
            $table->string('charter_project_name')->nullable()->after('unregistered_project');
        });
    }

    public function down()
    {
        Schema::table('shift_project_vehicle', function (Blueprint $table) {
            $table->dropColumn('charter_project_name');
        });
    }
};
