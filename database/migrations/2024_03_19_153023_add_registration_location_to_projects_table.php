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
        Schema::table('projects', function (Blueprint $table) {
            // 1 : 通常の作成  2 : チャーターリストから作成
            $table->unsignedInteger('registration_location')->default(1)->after('overtime_hourly_wage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // ロールバック時にカラムを削除
            $table->dropColumn('registration_location');
        });
    }
};
