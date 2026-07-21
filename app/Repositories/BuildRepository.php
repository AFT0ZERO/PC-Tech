<?php

namespace App\Repositories;

use App\Models\Build;
use App\Models\BuildItem;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class BuildRepository
{
    /**
     * Categories that participate in the PC Builder, in slot order.
     * Data-driven via the build_slots table — no hardcoded category names.
     */
    public function findBuilderCategories()
    {
        return Category::whereHas('buildSlot')
            ->with('buildSlot')
            ->get()
            ->sortBy(fn (Category $c) => $c->buildSlot->id)
            ->values();
    }

    public function findPartsByCategory(Category $category)
    {
        return Product::with('stores')
            ->select(
                'products.*',
                DB::raw('(SELECT MIN(product_price) FROM store_product WHERE store_product.product_id = products.id) as cheapest_price')
            )
            ->where('category_id', $category->id)
            ->orderBy('name')
            ->get()
            ->map(fn ($p) => [
                'id'             => $p->id,
                'name'           => $p->name,
                'brand'          => $p->brand,
                'cheapest_price' => (float) ($p->cheapest_price ?? 0),
                'category_name'  => $p->category->name ?? '',
            ]);
    }

    public function createBuild(array $data): Build
    {
        return Build::create($data);
    }

    public function createBuildItem(array $data): BuildItem
    {
        return BuildItem::create($data);
    }

    public function getUserBuildsWithProducts(int $userId)
    {
        return Build::with(['products' => fn ($q) => $q
                ->with('category')
                ->select('products.*')
                ->selectSub(
                    '(SELECT MIN(product_price) FROM store_product WHERE store_product.product_id = products.id)',
                    'cheapest_price'
                ),
            ])
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get();
    }

    public function delete(Build $build): ?bool
    {
        return $build->delete();
    }

    public function allCategories()
    {
        return Category::all();
    }
}
