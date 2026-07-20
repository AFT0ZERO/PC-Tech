<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('storage_specs', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->primary();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->string('interface', 30);
            $table->integer('capacity_gb')->unsigned();
            $table->json('specs')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('storage_specs');
    }
};
