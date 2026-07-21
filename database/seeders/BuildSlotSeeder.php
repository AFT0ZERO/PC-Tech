<?php

namespace Database\Seeders;

use App\Models\BuildSlot;
use App\Models\Category;
use Illuminate\Database\Seeder;

/**
 * Seeds the PC Builder slot limits (min/max quantity per category in a build).
 *
 * Slots are matched to categories through categories.specs_table — never
 * through display names — so renaming a category ("PSU" vs "Power Supply")
 * cannot break the mapping. Non-destructive: updateOrCreate per category.
 */
class BuildSlotSeeder extends Seeder
{
    /**
     * specs_table => [min_qty, max_qty]
     */
    private const SLOTS = [
        'cpu_specs'          => [1, 1],
        'motherboard_specs'  => [1, 1],
        'ram_specs'          => [1, 4],
        'storage_specs'      => [1, 4],
        'gpu_specs'          => [0, 2],
        'psu_specs'          => [1, 1],
        'cpu_cooler_specs'   => [0, 1],
        'case_specs'         => [1, 1],
    ];

    public function run(): void
    {
        foreach (self::SLOTS as $specsTable => [$minQty, $maxQty]) {
            $category = Category::where('specs_table', $specsTable)->first();

            if ($category === null) {
                continue;
            }

            BuildSlot::updateOrCreate(
                ['category_id' => $category->id],
                ['min_qty' => $minQty, 'max_qty' => $maxQty],
            );
        }
    }
}
