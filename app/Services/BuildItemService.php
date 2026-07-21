<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Build;
use App\Models\BuildSlot;
use App\Models\Product;
use App\Support\BuildCompatibility\BuildCompatibilityChecker;
use Illuminate\Support\Facades\DB;

/**
 * Owns every write operation on a build's items: adding, removing and
 * re-quantifying components, enforcing the build_slots limits, and running
 * the compatibility checker after each mutation.
 *
 * Slot limits come from the build_slots table (data-driven — no hardcoded
 * category IDs or names). Compatibility violations are reported as warnings;
 * blocking on them is a UX decision left to the caller (see AddItemResult).
 */
final class BuildItemService
{
    public function __construct(
        private readonly BuildCompatibilityChecker $checker,
    ) {
    }

    public function addItem(Build $build, Product $product, int $quantity = 1): AddItemResult
    {
        if ($quantity < 1) {
            return new AddItemResult(added: false, blockedReason: 'Quantity must be at least 1.');
        }

        $slot = $this->slotFor($product);

        if ($slot === null) {
            return new AddItemResult(
                added: false,
                blockedReason: 'This product category is not part of the PC Builder.',
            );
        }

        if ($this->categoryQuantity($build, $product) + $quantity > $slot->max_qty) {
            return new AddItemResult(
                added: false,
                blockedReason: "This category allows at most {$slot->max_qty} item(s) per build.",
            );
        }

        DB::transaction(function () use ($build, $product, $quantity) {
            $existing = $build->items()->where('product_id', $product->id)->first();

            if ($existing !== null) {
                $existing->increment('quantity', $quantity);
            } else {
                $build->items()->create(['product_id' => $product->id, 'quantity' => $quantity]);
            }
        });

        // Checking happens after the mutation; violations are returned as
        // warnings — allowing-with-warning vs blocking is a product (UX)
        // decision, not a data-integrity one, so it stays with the caller.
        return new AddItemResult(added: true, violations: $this->check($build));
    }

    public function updateQuantity(Build $build, Product $product, int $quantity): AddItemResult
    {
        if ($quantity < 1) {
            return $this->removeItem($build, $product);
        }

        $slot = $this->slotFor($product);

        if ($slot !== null && $quantity > $slot->max_qty) {
            return new AddItemResult(
                added: false,
                blockedReason: "This category allows at most {$slot->max_qty} item(s) per build.",
            );
        }

        $item = $build->items()->where('product_id', $product->id)->first();

        if ($item === null) {
            return new AddItemResult(added: false, blockedReason: 'This product is not in the build.');
        }

        $item->update(['quantity' => $quantity]);

        return new AddItemResult(added: true, violations: $this->check($build));
    }

    public function removeItem(Build $build, Product $product): AddItemResult
    {
        $build->items()->where('product_id', $product->id)->delete();

        return new AddItemResult(added: true, violations: $this->check($build));
    }

    /** @return \App\Support\BuildCompatibility\RuleViolation[] */
    public function check(Build $build): array
    {
        return $this->checker->check($build->fresh() ?? $build);
    }

    /** The build slot governing this product's category, if any. */
    private function slotFor(Product $product): ?BuildSlot
    {
        return BuildSlot::where('category_id', $product->category_id)->first();
    }

    /** Total units of this product's category already in the build. */
    private function categoryQuantity(Build $build, Product $product): int
    {
        return (int) $build->items()
            ->whereHas('product', fn ($q) => $q->where('category_id', $product->category_id))
            ->sum('quantity');
    }
}
