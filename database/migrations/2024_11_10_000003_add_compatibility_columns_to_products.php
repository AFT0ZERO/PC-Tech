<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('socket', 50)->nullable()->after('brand');
            $table->string('form_factor', 50)->nullable()->after('socket');
            $table->smallInteger('tdp')->unsigned()->nullable()->after('form_factor');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['socket', 'form_factor', 'tdp']);
        });
    }
};
