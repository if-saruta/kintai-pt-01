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
            $table->unsignedBigInteger('related_shift_project_vehicle_id')->nullable()->after('id');

            $table->foreign('related_shift_project_vehicle_id')
                  ->references('id')
                  ->on('shift_project_vehicle')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shift_project_vehicle', function (Blueprint $table) {
            $table->dropForeign(['related_shift_project_vehicle_id']);
            $table->dropColumn('related_shift_project_vehicle_id');
        });
    }
};
