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
        Schema::table('shift_project_vehicle', function (Blueprint $table) {
            $table->string('custom_project_name')->nullable()->after('project_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shift_project_vehicle', function (Blueprint $table) {
            $table->dropColumn('custom_project_name');
        });
    }
};
