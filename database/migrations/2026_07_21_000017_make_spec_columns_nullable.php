<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * The compatibility rules treat a NULL spec value as "unknown" and skip the
 * check instead of producing a false positive. The spec columns must
 * therefore accept NULL — partially-known specs (backfilled from a
 * description, or a form where the user left a field blank) must still be
 * storable.
 */
return new class extends Migration
{
    /**
     * table => [column => full column definition for MODIFY]
     */
    private const COLUMNS = [
        'cpu_specs' => [
            'socket' => 'VARCHAR(50) NULL',
        ],
        'motherboard_specs' => [
            'socket' => 'VARCHAR(50) NULL',
            'supported_ram_type' => 'VARCHAR(20) NULL',
            'ram_slots' => 'TINYINT UNSIGNED NULL',
            'max_ram_capacity_gb' => 'INT UNSIGNED NULL',
            'form_factor' => 'VARCHAR(30) NULL',
        ],
        'ram_specs' => [
            'type' => 'VARCHAR(20) NULL',
            'capacity_gb' => 'INT UNSIGNED NULL',
        ],
        'storage_specs' => [
            'interface' => 'VARCHAR(30) NULL',
            'capacity_gb' => 'INT UNSIGNED NULL',
        ],
        'gpu_specs' => [
            'length_mm' => 'INT UNSIGNED NULL',
            'vram_gb' => 'INT UNSIGNED NULL',
        ],
        'psu_specs' => [
            'wattage' => 'INT UNSIGNED NULL',
        ],
        'case_specs' => [
            'supported_form_factors' => 'JSON NULL',
            'max_gpu_length_mm' => 'INT UNSIGNED NULL',
            'max_cooler_height_mm' => 'INT UNSIGNED NULL',
        ],
        'cpu_cooler_specs' => [
            'supported_sockets' => 'JSON NULL',
            'height_mm' => 'INT UNSIGNED NULL',
        ],
    ];

    public function up(): void
    {
        foreach (self::COLUMNS as $table => $columns) {
            foreach ($columns as $column => $definition) {
                DB::statement("ALTER TABLE `{$table}` MODIFY `{$column}` {$definition}");
            }
        }
    }

    public function down(): void
    {
        foreach (self::COLUMNS as $table => $columns) {
            foreach ($columns as $column => $definition) {
                DB::statement("ALTER TABLE `{$table}` MODIFY `{$column}` " . str_replace(' NULL', ' NOT NULL', $definition));
            }
        }
    }
};
