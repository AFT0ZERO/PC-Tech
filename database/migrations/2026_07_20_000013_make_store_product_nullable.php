<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('store_product', function (Blueprint $table) {
            $table->decimal('product_price', 8, 2)->nullable()->change();
            $table->string('product_url')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('store_product', function (Blueprint $table) {
            $table->decimal('product_price', 8, 2)->nullable(false)->change();
            $table->string('product_url')->nullable(false)->change();
        });
    }
};
