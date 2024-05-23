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
            // 新しいカラムの追加
            $table->string('overtime_type')->nullable()->after('expressway_fee');

            // 残業代カラムのデータ型変更
            $table->decimal('overtime_fee', 8, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shift_project_vehicle', function (Blueprint $table) {
            // 新しいカラムの削除
            $table->string('overtime_type')->nullable()->after('expressway_fee');

            // 残業代カラムのデータ型を元に戻す
            $table->unsignedInteger('overtime_fee')->nullable()->change();
        });
    }
};
