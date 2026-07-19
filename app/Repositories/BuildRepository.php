<?php

namespace App\Repositories;

use App\Models\Build;
use App\Models\BuildPart;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BuildRepository
{
    public function findBuilderCategories()
    {
        $slotNames = ['CPU', 'Motherboard', 'RAM', 'GPU', 'Storage', 'PSU', 'Cooler', 'Case'];

        return Category::whereIn(
            DB::raw('LOWER(name)'),
            array_map('strtolower', $slotNames)
        )->get()->sortBy(fn ($c) => array_search(strtolower($c->name), array_map('strtolower', $slotNames)));
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

    public function calculateTotalPrice(array $partIds): float
    {
        return (float) Product::select(
                'products.id',
                DB::raw('(SELECT MIN(product_price) FROM store_product WHERE store_product.product_id = products.id) as cheapest_price')
            )
            ->whereIn('products.id', $partIds)
            ->get()
            ->sum(fn ($p) => (float) ($p->cheapest_price ?? 0));
    }

    public function createBuild(array $data): Build
    {
        return Build::create($data);
    }

    public function createBuildPart(array $data): BuildPart
    {
        return BuildPart::create($data);
    }

    public function getUserBuildsWithProducts(int $userId)
    {
        return Build::with(['products' => fn ($q) => $q->withPivot('category_name')])
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
