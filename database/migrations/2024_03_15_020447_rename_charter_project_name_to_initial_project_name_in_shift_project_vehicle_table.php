<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
            // MySQL 5.7対応のカラム名変更
        DB::statement('ALTER TABLE shift_project_vehicle RENAME COLUMN charter_project_name TO initial_project_name;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
            // 元に戻す場合
        DB::statement('ALTER TABLE shift_project_vehicle RENAME COLUMN initial_project_name TO charter_project_name;');
    }
};
