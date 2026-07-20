<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_specs', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->primary();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->json('supported_form_factors');
            $table->integer('max_gpu_length_mm')->unsigned();
            $table->integer('max_cooler_height_mm')->unsigned();
            
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_specs');
    }
};
