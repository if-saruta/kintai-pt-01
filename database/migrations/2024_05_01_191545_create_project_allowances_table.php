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
        Schema::create('project_allowances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id'); // 案件ID
            $table->string('name');
            $table->integer('retail_amount'); // 手当の金額
            $table->integer('driver_amount'); // 手当の金額
            $table->boolean('is_required')->default(false);
            $table->timestamps();

            // 外部キー制約
            $table->foreign('project_id')->references('id')->on('projects');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_allowances');
    }
};
