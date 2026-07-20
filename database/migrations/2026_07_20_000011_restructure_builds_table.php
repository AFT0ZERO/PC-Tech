<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('builds', 'pc_builds');

        Schema::table('pc_builds', function (Blueprint $table) {
            $table->dropColumn(['total_price', 'notes']);
            $table->boolean('is_public')->default(false)->after('name');
        });

        DB::statement('ALTER TABLE pc_builds MODIFY user_id BIGINT UNSIGNED NULL');
    }

    public function down(): void
    {
        DB::statement('UPDATE pc_builds SET user_id = 1 WHERE user_id IS NULL');
        DB::statement('ALTER TABLE pc_builds MODIFY user_id BIGINT UNSIGNED NOT NULL');

        Schema::table('pc_builds', function (Blueprint $table) {
            $table->dropColumn('is_public');
            $table->decimal('total_price', 10, 2)->default(0);
            $table->text('notes')->nullable();
        });

        Schema::rename('pc_builds', 'builds');
    }
};
