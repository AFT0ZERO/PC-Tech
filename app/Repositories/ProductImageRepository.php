<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductImage;

class ProductImageRepository
{
    public function findByProductId(int $productId)
    {
        return ProductImage::where('product_id', $productId)->get();
    }

    public function findProductById(int $id): ?Product
    {
        return Product::findOrFail($id);
    }

    public function findOrFail(int $id): ?ProductImage
    {
        return ProductImage::findOrFail($id);
    }

    public function bulkInsert(array $data): void
    {
        ProductImage::insert($data);
    }

    public function delete(ProductImage $productImage): ?bool
    {
        return $productImage->delete();
    }

    public function pathExists(string $path): bool
    {
        return \Illuminate\Support\Facades\File::exists($path);
    }

    public function deleteFile(string $path): void
    {
        \Illuminate\Support\Facades\File::delete($path);
    }
}
