<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cpu_specs', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->primary();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->string('socket', 50);
            $table->json('specs')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cpu_specs');
    }
};
