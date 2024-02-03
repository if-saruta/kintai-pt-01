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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('register_number');
            $table->string('name');
            $table->string('post_code');
            $table->string('address');
            $table->string('phone', 20)->nullable(); // 電話番号
            $table->string('fax', 20)->nullable();   // FAX番号
            $table->string('bank_name'); // 銀行名
            $table->string('account_holder_name'); // 口座名義人
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
