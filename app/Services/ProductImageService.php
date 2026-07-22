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

        if ($this->productImageRepository->pathExists($productImage->image)) {
            $this->productImageRepository->deleteFile($productImage->image);
        }

        $this->productImageRepository->delete($productImage);
    }

    public function replaceImage(int $imageId, $file): void
    {
        $productImage = $this->productImageRepository->findOrFail($imageId);

        if ($this->productImageRepository->pathExists($productImage->image)) {
            $this->productImageRepository->deleteFile($productImage->image);
        }

        $extension = $file->getClientOriginalExtension();
        $filename = uniqid() . '-' . time() . '.' . $extension;
        $path = 'uploads/ProductImage/';
        $file->move($path, $filename);

        $this->productImageRepository->update($productImage, ['image' => $path . $filename]);
    }
}
