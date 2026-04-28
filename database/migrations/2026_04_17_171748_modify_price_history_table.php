<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add the new sp_id column (nullable initially to allow for data transfer)
        Schema::table('price_history', function (Blueprint $table) {
            $table->unsignedBigInteger('sp_id')->nullable()->after('id');
        });

        // 2. Transfer data from old columns to new relation
        DB::statement("
            UPDATE price_history ph
            INNER JOIN stores s ON ph.store_name = s.name
            INNER JOIN store_product sp ON sp.product_id = ph.product_id AND sp.store_id = s.id
            SET ph.sp_id = sp.id
        ");

        // 3. Delete any price history entries that couldn't be matched
        // to a store_product pivot (orphans). Optional, but necessary before foreign key constraint.
        DB::table('price_history')->whereNull('sp_id')->delete();

        // 4. Make sp_id not nullable, add foreign key constraint, and drop old columns
        Schema::table('price_history', function (Blueprint $table) {
            $table->foreign('sp_id')->references('id')->on('store_product')->cascadeOnDelete();
            
            $table->dropForeign(['product_id']);
            $table->dropColumn(['product_id', 'store_name', 'store_url']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('price_history', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Product::class)->nullable()->constrained()->cascadeOnDelete();
            $table->string('store_name')->nullable();
            $table->string('store_url')->nullable();
        });

        DB::statement("
            UPDATE price_history ph
            INNER JOIN store_product sp ON ph.sp_id = sp.id
            INNER JOIN stores s ON sp.store_id = s.id
            SET ph.product_id = sp.product_id, ph.store_name = s.name, ph.store_url = sp.product_url
        ");

        Schema::table('price_history', function (Blueprint $table) {
            $table->dropForeign(['sp_id']);
            $table->dropColumn('sp_id');
        });
    }
};
