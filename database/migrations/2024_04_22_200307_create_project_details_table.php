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
        Schema::create('project_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained(); // 案件ID、案件テーブルの外部キー
            $table->string('delivery_type')->nullable(); // 配達種類
            $table->string('arrival_location')->nullable(); // 着車場所
            $table->string('delivery_area')->nullable(); // 配達エリア
            $table->string('delivery_address')->nullable(); // 納品先
            $table->string('cargo_type')->nullable(); // 荷物の種類
            $table->string('arrival_time')->nullable(); // 着車時間
            $table->string('finish_time')->nullable(); // 終了時間
            $table->string('count')->nullable(); // 件数
            $table->string('operation_date')->nullable(); // 稼働日
            $table->string('vehicle')->nullable(); // 車両
            $table->string('cash_on_delivery')->nullable(); // 代引き
            $table->string('retail_price_for_hgl')->nullable(); // HGL用上代
            $table->string('notes')->nullable(); // 備考欄
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_details');
    }
};
