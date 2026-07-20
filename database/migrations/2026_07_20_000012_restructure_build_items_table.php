<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE build_parts DROP FOREIGN KEY build_parts_build_id_foreign');

        Schema::rename('build_parts', 'build_items');

        Schema::table('build_items', function (Blueprint $table) {
            $table->dropColumn('category_name');
            $table->smallInteger('quantity')->unsigned()->default(1)->after('product_id');
        });

        Schema::table('build_items', function (Blueprint $table) {
            $table->foreign('build_id')->references('id')->on('pc_builds')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE build_items DROP FOREIGN KEY build_items_build_id_foreign');

        Schema::table('build_items', function (Blueprint $table) {
            $table->dropColumn('quantity');
            $table->string('category_name', 100)->after('product_id');
        });

        Schema::rename('build_items', 'build_parts');

        Schema::table('build_parts', function (Blueprint $table) {
            $table->foreign('build_id')->references('id')->on('builds')->cascadeOnDelete();
        });
    }
};
