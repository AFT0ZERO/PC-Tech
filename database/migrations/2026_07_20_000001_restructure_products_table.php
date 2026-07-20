<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('power_draw_watts')->nullable()->after('brand');
        });

        DB::statement('ALTER TABLE products CHANGE smallDescription small_description VARCHAR(255)');
        DB::statement('ALTER TABLE products MODIFY description LONGTEXT');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['socket', 'form_factor', 'tdp']);
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('power_draw_watts');
            $table->string('socket', 50)->nullable()->after('brand');
            $table->string('form_factor', 50)->nullable()->after('socket');
            $table->smallInteger('tdp')->unsigned()->nullable()->after('form_factor');
        });

        DB::statement('ALTER TABLE products CHANGE small_description smallDescription VARCHAR(255)');
        DB::statement('ALTER TABLE products MODIFY description JSON');
    }
};
