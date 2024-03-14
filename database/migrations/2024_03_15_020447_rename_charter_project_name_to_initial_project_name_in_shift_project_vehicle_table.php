<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up() {
        DB::statement('ALTER TABLE shift_project_vehicle CHANGE charter_project_name initial_project_name VARCHAR(255);');
    }

    public function down() {
        DB::statement('ALTER TABLE shift_project_vehicle CHANGE initial_project_name charter_project_name VARCHAR(255);');
    }
};
