<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\CategoryRepository;
use App\Repositories\PriceHistoryRepository;
use App\Repositories\ProductRepository;
use App\Repositories\StoreProductRepository;
use App\Services\ScraperConfigService;
use Illuminate\Support\Str;

class ProductService
{
    public function __construct(
        private ProductRepository $productRepository,
        private StoreProductRepository $storeProductRepository,
        private CategoryRepository $categoryRepository,
        private PriceHistoryRepository $priceHistoryRepository,
        private ScraperConfigService $scraperConfigService,
    ) {
    }

    public function list(?string $search = null, ?int $categoryId = null, string $sort = 'name_asc')
    {
        return $this->productRepository->paginateAdminIndex($search, $categoryId, $sort);
    }

    public function getFormData(): array
    {
        return [
            'categories' => $this->categoryRepository->allOrderedByName(),
            'stores'     => $this->storeProductRepository->allStoresList(),
        ];
    }

    public function getStores()
    {
        return $this->storeProductRepository->allStoresList();
    }

    public function create(array $productData, array $storeInputs, ?string $specsTable = null, ?array $specData = null): Product
    {
        $product = $this->productRepository->create($productData);

        foreach ($storeInputs as $input) {
            $this->storeProductRepository->attach($product, $input['store_id'], [
                'product_price'  => $input['price'],
                'product_url'    => $input['url'],
                'product_status' => $input['status'],
            ]);
        }

        if ($specsTable && $specData) {
            $specModelClass = 'App\\Models\\' . Str::studly(Str::singular($specsTable));
            if (class_exists($specModelClass)) {
                $specModelClass::create(['product_id' => $product->id] + $specData);
            }
        }

        return $product;
    }

    public function getEditData(Product $product): array
    {
        $product->load('stores');
        $description = json_decode($product->description, true);

        return [
            'categories'   => $this->categoryRepository->all(),
            'stores'       => $this->storeProductRepository->allStoresList(),
            'product'      => $product,
            'descriptions' => $description,
        ];
    }

    public function getShowData(Product $product): array
    {
        $product->load(['images', 'stores', 'category']);
        $description = json_decode($product->description, true);

        $allHistory = $this->priceHistoryRepository->getAllHistoryForProduct($product->id);
        $priceHistory = $allHistory->groupBy('store_name')->map(fn ($rows) => $rows->last());

        return [
            'product'      => $product,
            'descriptions' => $description,
            'priceHistory' => $priceHistory,
            'allHistory'   => $allHistory,
        ];
    }

    public function update(Product $product, array $data): void
    {
        $product->name = $data['name'];
        $product->brand = $data['brand'];
        $product->smallDescription = $data['smallDescription'];
        $product->category_id = $data['category_id'];
        $product->description = json_encode($data['description']);
        $this->productRepository->save($product);
    }

    public function updateExistingStorePivot(Product $product, array $storeUpdates): void
    {
        foreach ($storeUpdates as $storeId => $update) {
            if (!empty($update['prices'])) {
                $this->storeProductRepository->updateExistingPivot($product, $storeId, [
                    'product_price'  => $update['prices'][0],
                    'product_url'    => $update['urls'][0]   ?? '',
                    'product_status' => $update['status'][0] ?? 'out of stock',
                ]);
            }
        }
    }

    public function attachNewStores(Product $product, array $newStores): void
    {
        foreach ($newStores as $store) {
            if (empty($store['store_id'])) {
                continue;
            }
            if ($this->storeProductRepository->exists($product, $store['store_id'])) {
                continue;
            }
            $this->storeProductRepository->attach($product, $store['store_id'], [
                'product_price'  => $store['price'] ?? 0,
                'product_url'    => $store['url']   ?? '',
                'product_status' => $store['status'] ?? 'out of stock',
            ]);
        }
    }

    public function delete(Product $product): void
    {
        $this->productRepository->delete($product);
    }

    public function syncScraperConfig(): void
    {
        $this->scraperConfigService->sync();
    }
}
