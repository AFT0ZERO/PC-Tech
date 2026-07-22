<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_scraper_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Store::class)->constrained()->cascadeOnDelete();
            $table->enum('mode', ['static', 'dynamic'])->default('static');
            $table->integer('delay')->default(3);
            $table->json('price_selectors');
            $table->string('currency', 3)->default('JOD');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_scraper_configs');
    }
};
