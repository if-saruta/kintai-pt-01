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
        Schema::table('employees', function (Blueprint $table) {
            $table->integer('transfer_fee')->nullable()->after('employment_status'); // 振込手数料カラムを追加
            $table->text('shift_memo')->nullable()->after('remarks'); // シフトメモカラムを追加
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('transfer_fee'); // 振込手数料カラムを削除
            $table->dropColumn('shift_memo'); // シフトメモカラムを削除
        });
    }
};
