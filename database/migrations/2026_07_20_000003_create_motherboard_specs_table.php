<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('motherboard_specs', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->primary();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->string('socket', 50);
            $table->string('supported_ram_type', 20);
            $table->tinyInteger('ram_slots')->unsigned();
            $table->integer('max_ram_capacity_gb')->unsigned();
            $table->string('form_factor', 30);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('motherboard_specs');
    }
};
