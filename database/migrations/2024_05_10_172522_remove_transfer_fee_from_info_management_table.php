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
        Schema::table('info_management', function (Blueprint $table) {
            Schema::table('info_management', function (Blueprint $table) {
                $table->dropColumn('transfer_fee');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('info_management', function (Blueprint $table) {
            Schema::table('info_management', function (Blueprint $table) {
                $table->integer('transfer_fee')->nullable();
            });
        });
    }
};
