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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('name');
            $table->string('initials')->nullable();
            $table->string('post_code');
            $table->string('address');
            $table->string('employment_status')->nullable();
            $table->boolean('is_invoice')->default(false);
            $table->string('register_number')->nullable();
            $table->string('vehicle_rental_type')->nullable();// 0 : 自車 1 : 月リース 2 : なんでも月リース 3 : 日割り
            $table->foreignId('vehicle_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

            // 外部キー制約
            // $table->foreign('employment_status_id')->references('id')->on('employment_statuses')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
