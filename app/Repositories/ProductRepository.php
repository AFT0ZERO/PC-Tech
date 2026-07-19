<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductRepository
{
    public function paginateWithMinPrice(int $perPage = 7, ?int $categoryId = null)
    {
        $query = Product::with('stores')
            ->select('products.*', DB::raw('(SELECT MIN(product_price) FROM store_product WHERE store_product.product_id = products.id) as cheapest_price'));

        if ($categoryId !== null) {
            $query->where('category_id', $categoryId);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function searchWithMinPrice(string $query, int $perPage = 15)
    {
        return Product::search($query)->paginate($perPage);
    }

    public function paginateAllWithMinPrice(int $perPage = 15)
    {
        return Product::with('stores')
            ->select('products.*', DB::raw('(SELECT MIN(product_price) FROM store_product WHERE store_product.product_id = products.id) as cheapest_price'))
            ->paginate($perPage);
    }

    public function paginateByCategoryWithMinPrice(int $categoryId, int $perPage = 15)
    {
        return Product::with('stores')
            ->select('products.*', DB::raw('(SELECT MIN(product_price) FROM store_product WHERE store_product.product_id = products.id) as cheapest_price'))
            ->whereHas('category', function ($query) use ($categoryId) {
                $query->where('id', $categoryId);
            })->paginate($perPage);
    }

    public function brandCounts(?int $categoryId = null)
    {
        $query = Product::selectRaw('brand, COUNT(*) as product_count')
            ->groupBy('brand');

        if ($categoryId !== null && $categoryId > 0) {
            $query->whereHas('category', function ($q) use ($categoryId) {
                $q->where('id', $categoryId);
            });
        }

        return $query->get();
    }

    public function findWithRelations(int $id)
    {
        return Product::with('stores', 'images', 'category')->find($id);
    }

    public function relatedProducts(int $categoryId, int $excludeId, int $perPage = 15)
    {
        return Product::where('category_id', $categoryId)->with('stores')
            ->select('products.*', DB::raw('(SELECT MIN(product_price) FROM store_product WHERE store_product.product_id = products.id) as cheapest_price'))
            ->paginate($perPage);
    }

    public function paginateAdminIndex(?string $search = null, ?int $categoryId = null, string $sort = 'name_asc', int $perPage = 25)
    {
        $query = Product::query()->with('category');

        if (!empty($search)) {
            $s = $search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', '%' . $s . '%')
                    ->orWhere('brand', 'like', '%' . $s . '%');
            });
        }

        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        match ($sort) {
            'name_desc' => $query->orderBy('name', 'desc'),
            'created_asc' => $query->orderBy('created_at', 'asc'),
            'created_desc' => $query->orderBy('created_at', 'desc'),
            default => $query->orderBy('name', 'asc'),
        };

        return $query->paginate($perPage)->withQueryString();
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function save(Product $product): bool
    {
        return $product->save();
    }

    public function delete(Product $product): ?bool
    {
        return $product->delete();
    }

    public function findWithTrashed(int $id): ?Product
    {
        return Product::withTrashed()->find($id);
    }

    public function restore(Product $product): bool
    {
        return $product->restore();
    }

    public function onlyTrashedPaginate(int $perPage = 15)
    {
        return Product::onlyTrashed()->paginate($perPage);
    }
}
