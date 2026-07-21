<?php

declare(strict_types=1);

namespace App\Support\BuildCompatibility;

use App\Models\Build;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent-agnostic snapshot of a build's contents at compatibility-check time.
 *
 * Every rule reads from this object only — no rule touches the Build model or the
 * database directly, which keeps every rule testable in isolation (pure unit tests,
 * no DB) and guarantees the checker performs a fixed, eager amount of queries.
 *
 * Spec instances are grouped by "category spec key" (derived from
 * categories.specs_table, e.g. cpu_specs => cpu — see Category::specKey()).
 */
final class BuildContext
{
    /**
     * @param  array<string, array<int, Model>>  $specsByCategory  spec key => one spec
     *         instance per physical unit (repeated when quantity > 1)
     */
    public function __construct(
        private array $specsByCategory = [],
        private float $totalPowerDraw = 0.0,
    ) {
    }

    public static function fromBuild(Build $build): self
    {
        $build->loadMissing([
            'items.product.category',
            ...array_map(fn (string $relation) => "items.product.$relation", Product::SPEC_RELATIONS),
        ]);

        $specsByCategory = [];
        $totalPowerDraw = 0.0;

        foreach ($build->items as $item) {
            $product = $item->product;
            $spec = $product->specModel();
            $powerDraw = (float) ($product->power_draw_watts ?? 0);

            if ($spec !== null && ($key = $product->category->specKey()) !== null) {
                // Pin the power draw onto the spec instance itself, so AggregateRule
                // reads it like any other field without knowing about Product.
                $spec->setAttribute('power_draw_watts', $powerDraw);

                for ($i = 0; $i < $item->quantity; $i++) {
                    $specsByCategory[$key][] = $spec;
                }
            }

            $totalPowerDraw += $powerDraw * $item->quantity;
        }

        return new self($specsByCategory, $totalPowerDraw);
    }

    /** First (and usually only) part of this category — for single-slot categories (cpu, motherboard, psu, case...). */
    public function specOf(string $categoryKey): ?Model
    {
        return $this->specsByCategory[$categoryKey][0] ?? null;
    }

    /**
     * Every unit of this category — for multi-slot categories (ram, storage, gpu).
     *
     * @return array<int, Model>
     */
    public function specsOf(string $categoryKey): array
    {
        return $this->specsByCategory[$categoryKey] ?? [];
    }

    public function hasCategory(string $categoryKey): bool
    {
        return isset($this->specsByCategory[$categoryKey]);
    }

    /** Sum of products.power_draw_watts over every unit in the build. */
    public function totalPowerDraw(): float
    {
        return $this->totalPowerDraw;
    }
}
