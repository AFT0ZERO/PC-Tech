<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Repositories\CategoryRepository;
use App\Repositories\ComponentSpecReader;
use App\Repositories\PriceHistoryRepository;
use App\Repositories\ProductRepository;
use App\Repositories\StoreProductRepository;
use App\Services\ScraperConfigService;
use Illuminate\Support\Str;

class ProductService
{
    private const SPEC_MAP = [
        'gpu_specs' => [
            'length_mm' => 'length',
            'vram_gb'   => 'memory',
        ],
        'case_specs' => [
            'supported_form_factors'  => 'supported_motherboard_form_factors',
            'max_gpu_length_mm'       => 'max_video_card_length',
            'max_cooler_height_mm'    => 'max_cpu_cooler_height',
        ],
        'cpu_cooler_specs' => [
            'supported_sockets' => 'cpu_sockets',
            'height_mm'         => 'height',
        ],
        'ram_specs' => [
            'type'        => 'ram_type',
            'capacity_gb' => 'capacity',
        ],
        'motherboard_specs' => [
            'supported_ram_type'   => 'memory.ram_type',
            'ram_slots'            => 'memory.slots',
            'max_ram_capacity_gb'  => 'memory.max',
            'socket'               => 'socket',
            'form_factor'          => 'form_factor',
        ],
        'storage_specs' => [
            'capacity_gb' => 'capacity',
        ],
    ];

    public function __construct(
        private ProductRepository $productRepository,
        private StoreProductRepository $storeProductRepository,
        private CategoryRepository $categoryRepository,
        private PriceHistoryRepository $priceHistoryRepository,
        private ScraperConfigService $scraperConfigService,
        private ?FormFieldResolver $fieldResolver = null,
        private ?ComponentSpecReader $componentSpecReader = null,
    ) {
        $this->fieldResolver ??= new FormFieldResolver;
        $this->componentSpecReader ??= new ComponentSpecReader;
    }

    public function getIndexData(?string $search = null, ?int $categoryId = null, string $sort = 'name_asc'): array
    {
        return [
            'products'   => $this->productRepository->paginateAdminIndex($search, $categoryId, $sort),
            'categories' => $this->categoryRepository->allOrderedByName(),
        ];
    }

    public function getCreateData(): array
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

    public function resolveFields(Category $category): array
    {
        return $this->fieldResolver->resolve($category);
    }

    public function autocomplete(string $query, int $categoryId): array
    {
        $category = Category::findOrFail($categoryId);

        if (empty($category->open_db_name)) {
            return ['enabled' => false, 'results' => []];
        }

        $dbPath = base_path('database/components.sqlite');

        if (!file_exists($dbPath)) {
            return ['enabled' => false, 'results' => []];
        }

        try {
            $rows = $this->componentSpecReader->searchAutocomplete(
                $query,
                $category->open_db_name,
                $dbPath
            );

            $map = $category->specs_table ? (self::SPEC_MAP[$category->specs_table] ?? []) : [];

            $results = array_map(function (array $row) use ($map, $category) {
                $specs = json_decode($row['specs_json'], true) ?? [];
                if ($map) {
                    $mapped = $this->mapSqliteSpecs($specs, $map);
                    $specs = array_merge($specs, $mapped);
                }
                if (!isset($specs['tdp'])) {
                    $specs['tdp'] = $category->specs_table === 'psu_specs' ? 0 : 1;
                }
                unset($specs['opendb_id']);
                return [
                    'name'  => $row['name'],
                    'specs' => $specs,
                ];
            }, $rows);

            return ['enabled' => true, 'results' => $results];
        } catch (\Exception $e) {
            return ['enabled' => false, 'results' => [], 'error' => $e->getMessage()];
        }
    }

    private function mapSqliteSpecs(array $specs, array $map): array
    {
        $mapped = [];
        foreach ($map as $column => $path) {
            $value = data_get($specs, $path);
            if ($value === null || $value === '' || $value === []) {
                continue;
            }
            $mapped[$column] = is_array($value) ? json_encode($value) : $value;
        }
        return $mapped;
    }

    public function storeProduct(array $validated): Product
    {
        $category = Category::findOrFail($validated['category']);
        $fields = $this->fieldResolver->resolve($category);

        $productData = ['category_id' => $category->id];
        foreach ($fields['product_fields'] as $field) {
            $productData[$field['name']] = $validated[$field['name']] ?? null;
        }

        if (!empty($validated['key']) && !empty($validated['value'])) {
            $productData['description'] = json_encode(
                array_combine($validated['key'], $validated['value'])
            );
        }

        $specData = null;
        $specsTable = $category->specs_table;

        if ($specsTable && !empty($fields['spec_fields'])) {
            $specData = [];
            foreach ($fields['spec_fields'] as $field) {
                $specData[$field['name']] = $validated[$field['name']] ?? null;
            }
        }

        $storeInputs = [];
        foreach ($validated['store_id'] ?? [] as $index => $storeId) {
            $price = $validated['price'][$index] ?? null;
            if (empty($storeId) || (is_string($price) && trim($price) === '') || $price === null) {
                continue;
            }

            $storeInputs[] = [
                'store_id' => $storeId,
                'price'    => $price,
                'url'      => $validated['url'][$index] ?? '',
                'status'   => $validated['status'][$index] ?? 'in stock',
            ];
        }

        return $this->create($productData, $storeInputs, $specsTable, $specData);
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
        $load = ['stores', 'category', 'images'];
        $relation = $product->specRelationName();
        if ($relation) {
            $load[] = $relation;
        }
        $product->load($load);

        $description = json_decode($product->description, true);

        $specData = [];
        if ($relation) {
            $specModel = $product->getRelation($relation);
            if ($specModel) {
                $specData = $specModel->toArray();
                unset($specData['product_id']);
            }
        }

        return [
            'categories'   => $this->categoryRepository->all(),
            'stores'       => $this->storeProductRepository->allStoresList(),
            'product'      => $product,
            'descriptions' => $description,
            'specData'     => $specData,
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

    public function restoreProduct(int $id): void
    {
        $product = $this->productRepository->findWithTrashed($id);
        $product->restore();
    }

    public function getTrashed()
    {
        return $this->productRepository->onlyTrashedPaginate();
    }

    public function syncScraperConfig(): void
    {
        $this->scraperConfigService->sync();
    }
}
