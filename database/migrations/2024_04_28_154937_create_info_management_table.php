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
        Schema::create('info_management', function (Blueprint $table) {
            $table->id();
            $table->decimal('tax_rate', 8, 2);  // 税率
            $table->decimal('discount_rate', 8, 2);  // 値引き率
            $table->unsignedBigInteger('monthly_lease_fee');  // 月リース料
            $table->unsignedBigInteger('monthly_lease_insurance_fee');  // 月リース保険料
            $table->unsignedBigInteger('monthly_lease_second_fee');  // 月リース二代目料
            $table->unsignedBigInteger('monthly_lease_second_insurance_fee');  // 月リース二代目保険料
            $table->unsignedBigInteger('prorated_lease_fee');  // 日割りリース料
            $table->unsignedBigInteger('prorated_insurance_fee');  // 日割り保険料
            $table->decimal('admin_commission_rate', 8, 2);  // 事務委託率
            $table->unsignedBigInteger('admin_fee_switch');  // 事務手数料切り替え上限
            $table->unsignedBigInteger('max_admin_fee');  // 最大事務手数料
            $table->unsignedBigInteger('min_admin_fee');  // 最小事務手数料
            $table->unsignedBigInteger('transfer_fee');  // 振込手数料
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('info_management');
    }
};
