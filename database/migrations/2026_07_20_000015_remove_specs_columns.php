<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('small_description');
        });

    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('small_description')->nullable()->after('description');
        });

    }
};
