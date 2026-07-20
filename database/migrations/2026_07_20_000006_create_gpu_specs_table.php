<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gpu_specs', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->primary();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->integer('length_mm')->unsigned();
            $table->integer('vram_gb')->unsigned();
            $table->json('specs')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gpu_specs');
    }
};
