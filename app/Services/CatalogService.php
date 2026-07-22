<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use App\Repositories\FaqRepository;
use App\Repositories\PriceHistoryRepository;
use App\Repositories\ProductRepository;
use App\Models\Feedback;

class CatalogService
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private ProductRepository $productRepository,
        private PriceHistoryRepository $priceHistoryRepository,
        private FaqRepository $faqRepository,
    ) {
    }

    public function getNavbarCategories()
    {
        return $this->categoryRepository->all();
    }

    public function autocompleteSearch(string $query): array
    {
        if (trim($query) === '') {
            return [];
        }
        return $this->productRepository->searchAutocomplete($query);
    }

    public function getLandingData(): array
    {
        return [
            'categories' => $this->categoryRepository->all(),
            'lastProducts' => $this->productRepository->paginateWithMinPrice(7),
            'CategoryProducts' => $this->productRepository->paginateWithMinPrice(7, 1),
        ];
    }

    public function getCategoryData(?int $id, ?string $search): array
    {
        $categories = $this->categoryRepository->all();
        $brandsWithCounts = $this->productRepository->brandCounts($id);
        $category = null;

        if (!empty($search)) {
            $products = $this->productRepository->searchWithMinPrice($search, 15);
        } elseif ($id == 0 || $id == null) {
            $products = $this->productRepository->paginateAllWithMinPrice(15);
        } elseif ($id > 0) {
            $products = $this->productRepository->paginateByCategoryWithMinPrice($id, 15);
            $category = \App\Models\Category::find($id);
        } else {
            $products = $this->productRepository->paginateAllWithMinPrice(15);
        }

        return [
            'categories' => $categories,
            'products' => $products,
            'brands' => $brandsWithCounts,
            'category' => $category,
        ];
    }

    public function getSinglePageData(int $id): array
    {
        $categories = $this->categoryRepository->all();
        $product = $this->productRepository->findWithRelations($id);

        $description = json_decode($product->description, true);
        $feedbacks = Feedback::where('product_id', $product->id)->get();
        $CategoryProduct = $this->productRepository->relatedProducts($product->category->id, $product->id, 15);

        $allHistory = $this->priceHistoryRepository->getAllHistoryForProduct($product->id);
        $priceHistory = $allHistory->groupBy('store_name')->map(fn($rows) => $rows->last());
        $priceHistoryChart = $allHistory->groupBy('store_name')->map(function ($rows) {
            return $rows->map(fn($r) => [
                'date'  => $r->scraped_at->format('Y-m-d H:i'),
                'price' => (float) $r->price,
            ])->values();
        });

        return [
            'product'           => $product,
            'description'       => $description,
            'CategoryProducts'  => $CategoryProduct,
            'categories'        => $categories,
            'feedbacks'         => $feedbacks,
            'priceHistory'      => $priceHistory,
            'priceHistoryChart' => $priceHistoryChart,
        ];
    }

    public function getFaqsPageData(): array
    {
        return [
            'categories' => $this->categoryRepository->all(),
            'faqs' => $this->faqRepository->all(),
        ];
    }

    public function getStaticPageData(string $page): array
    {
        return [
            'categories' => $this->categoryRepository->all(),
        ];
    }
}
