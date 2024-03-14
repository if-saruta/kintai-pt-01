<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('shift_project_vehicle', function (Blueprint $table) {
            $table->renameColumn('charter_project_name', 'initial_project_name');
        });
    }

    public function down()
    {
        Schema::table('shift_project_vehicle', function (Blueprint $table) {
            $table->renameColumn('initial_project_name', 'charter_project_name');
        });
    }
};
