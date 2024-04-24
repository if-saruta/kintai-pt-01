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
            // ポリモーフィックリレーションのためのカラム
            $table->string('ownership_type')->nullable()->after('id');
            $table->unsignedBigInteger('ownership_id')->nullable()->after('ownership_type');//所有者
            $table->unsignedBigInteger('employee_id')->nullable()->after('ownership_id'); //使用者
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->string('vehicle_type')->nullable()->after('ownership_id'); //車種
            $table->string('category')->nullable()->after('vehicle_type'); //種別
            $table->string('brand_name')->nullable()->after('category'); //社名
            $table->string('model')->nullable()->after('brand_name'); //型式
            $table->date('inspection_expiration_date')->nullable()->after('model'); //車両満了日
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            // 外部キー制約を先に削除
            $table->dropForeign(['employee_id']);
            // 追加したカラムとポリモーフィックリレーションのカラムを削除
            $table->dropColumn([
                'vehicle_type',
                'employee_id',
                'category',
                'brand_name',
                'model',
                'inspection_expiration_date',
                'ownership_id',
                'ownership_type',
            ]);
        });
    }
};
