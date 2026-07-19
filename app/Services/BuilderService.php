<?php

namespace App\Services;

use App\Models\Build;
use App\Models\Category;
use App\Models\Product;
use App\Repositories\BuildRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BuilderService
{
    public function __construct(
        private BuildRepository $buildRepository,
        private BuildCompatibilityService $compatibility,
    ) {
    }

    public function getIndexData(): array
    {
        return [
            'categories' => $this->buildRepository->allCategories(),
            'builderCategories' => $this->buildRepository->findBuilderCategories(),
        ];
    }

    public function getParts(Category $category)
    {
        return $this->buildRepository->findPartsByCategory($category);
    }

    public function checkCompatibility(array $partIds): array
    {
        return $this->compatibility->check($partIds);
    }

    public function saveBuild(string $name, ?string $notes, array $partIds): Build
    {
        $totalPrice = $this->buildRepository->calculateTotalPrice($partIds);

        $products = Product::with('category')->whereIn('id', $partIds)->get()->keyBy('id');

        return DB::transaction(function () use ($name, $notes, $totalPrice, $partIds, $products) {
            $build = $this->buildRepository->createBuild([
                'user_id'     => Auth::id(),
                'name'        => $name,
                'notes'       => $notes,
                'total_price' => $totalPrice,
            ]);

            foreach ($partIds as $productId) {
                $product = $products->get($productId);
                if ($product) {
                    $this->buildRepository->createBuildPart([
                        'build_id'      => $build->id,
                        'product_id'    => $productId,
                        'category_name' => $product->category->name ?? 'Unknown',
                    ]);
                }
            }

            return $build;
        });
    }

    public function getMyBuilds(int $userId): array
    {
        return [
            'categories' => $this->buildRepository->allCategories(),
            'builds' => $this->buildRepository->getUserBuildsWithProducts($userId),
        ];
    }

    public function deleteBuild(Build $build): void
    {
        $this->buildRepository->delete($build);
    }
}
