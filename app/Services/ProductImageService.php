<?php

namespace App\Services;

use App\Repositories\ProductImageRepository;

class ProductImageService
{
    public function __construct(
        private ProductImageRepository $productImageRepository,
        private ImageUploadService $imageUploadService,
    ) {
    }

    public function getIndexData(int $productId): array
    {
        $product = $this->productImageRepository->findProductById($productId);
        $images = $this->productImageRepository->findByProductId($productId);

        return [
            'ProductImages' => $images,
            'product' => $product,
        ];
    }

    public function uploadImages(int $productId, array $files): void
    {
        $product = $this->productImageRepository->findProductById($productId);

        $imageData = [];
        foreach ($files as $key => $file) {
            $extension = $file->getClientOriginalExtension();
            $filename = $key . '-' . time() . '.' . $extension;
            $path = "uploads/ProductImage/";
            $file->move($path, $filename);

            $imageData[] = [
                'product_id' => $product->id,
                'image' => $path . $filename,
            ];
        }

        $this->productImageRepository->bulkInsert($imageData);
    }

    public function deleteImage(int $id, int $productId): void
    {
        $productImage = $this->productImageRepository->findOrFail($id);

        // G9 preserved: using $ProductImage->path which doesn't exist (column is 'image')
        if ($this->productImageRepository->pathExists($productImage->path)) {
            $this->productImageRepository->deleteFile($productImage->path);
        }

        $this->productImageRepository->delete($productImage);
    }
}
