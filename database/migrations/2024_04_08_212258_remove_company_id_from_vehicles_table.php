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
        Schema::table('vehicles', function (Blueprint $table) {
            // 外部キー制約を削除
            $table->dropForeign(['company_id']);
            // company_idカラムを削除
            $table->dropColumn('company_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            // company_idカラムを追加
            $table->unsignedBigInteger('company_id')->nullable()->after('number');
            // 外部キー制約を再追加
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }
};
