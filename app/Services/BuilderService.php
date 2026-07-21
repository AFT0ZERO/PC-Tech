<?php

namespace App\Services;

use App\Models\Build;
use App\Models\BuildItem;
use App\Models\Category;
use App\Models\Product;
use App\Repositories\BuildRepository;
use App\Support\BuildCompatibility\BuildCompatibilityChecker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Application service behind the PC Builder pages: slot categories, part
 * listing, on-the-fly compatibility checks and saving whole builds.
 *
 * Item-level mutations of an already persisted build live in BuildItemService;
 * this service coordinates page-level workflows and delegates all
 * compatibility knowledge to BuildCompatibilityChecker.
 */
class BuilderService
{
    public function __construct(
        private BuildRepository $buildRepository,
        private BuildCompatibilityChecker $checker,
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

    public function getPartsPageData(Category $category): array
    {
        $slot = $category->buildSlot;
        $maxQty = $slot ? (int)$slot->max_qty : 1;

        $products = Product::with(['category', 'images'])
            ->where('category_id', $category->id)
            ->select('products.*')
            ->addSelect([
                'cheapest_price' => DB::table('store_product')
                    ->selectRaw('MIN(product_price)')
                    ->whereColumn('product_id', 'products.id'),
                'cheapest_store_name' => DB::table('store_product')
                    ->join('stores', 'stores.id', '=', 'store_product.store_id')
                    ->select('stores.name')
                    ->whereColumn('store_product.product_id', 'products.id')
                    ->orderBy('store_product.product_price')
                    ->limit(1),
                'cheapest_status' => DB::table('store_product')
                    ->select('product_status')
                    ->whereColumn('product_id', 'products.id')
                    ->orderBy('product_price')
                    ->limit(1),
            ])
            ->orderBy('name')
            ->get();

        return [
            'category' => $category,
            'products' => $products,
            'maxQty' => $maxQty,
        ];
    }

    /**
     * Compatibility warnings for an arbitrary selection of product IDs
     * (the not-yet-saved build the user is composing on the builder page).
     *
     * @param  array<int>  $partIds
     * @return array<string> human-readable warnings; empty = compatible
     */
    public function checkCompatibility(array $partIds): array
    {
        if (empty($partIds)) {
            return [];
        }

        return $this->compatibilityWarnings($this->transientBuild($partIds));
    }

    /**
     * Human-readable notes about selected parts whose compatibility could not
     * be verified because their spec row is missing (unknown ≠ compatible).
     *
     * @param  array<int>  $partIds
     * @return array<string>
     */
    public function missingSpecNotes(array $partIds): array
    {
        if (empty($partIds)) {
            return [];
        }

        $notes = [];

        foreach ($this->transientBuild($partIds)->items as $item) {
            $product = $item->product;

            if ($product->category?->specKey() !== null && $product->specModel() === null) {
                $notes[] = "Compatibility could not be verified for {$product->name} — its specifications are missing.";
            }
        }

        return $notes;
    }

    /**
     * @return array<string>
     */
    public function compatibilityWarnings(Build $build): array
    {
        return array_map(
            fn ($violation) => $violation->message,
            $this->checker->check($build),
        );
    }

    /**
     * Persist a new build with one item per selected product
     * (duplicate IDs in the selection become the item quantity).
     *
     * @param  array<int>  $partIds
     */
    public function saveBuild(string $name, array $partIds): Build
    {
        $quantities = array_count_values($partIds);

        return DB::transaction(function () use ($name, $quantities) {
            $build = $this->buildRepository->createBuild([
                'user_id' => Auth::id(),
                'name'    => $name,
            ]);

            foreach ($quantities as $productId => $quantity) {
                $this->buildRepository->createBuildItem([
                    'build_id'   => $build->id,
                    'product_id' => $productId,
                    'quantity'   => $quantity,
                ]);
            }

            return $build;
        });
    }

    /**
     * An unsaved Build carrying the given product selection, with every
     * relation the compatibility engine needs already loaded — used to run
     * the checker without persisting anything.
     *
     * @param  array<int>  $partIds
     */
    public function transientBuild(array $partIds): Build
    {
        $quantities = array_count_values($partIds);

        $products = Product::with(['category', ...Product::SPEC_RELATIONS])
            ->whereIn('id', array_keys($quantities))
            ->get();

        $items = $products
            ->map(fn (Product $p) => (new BuildItem([
                'product_id' => $p->id,
                'quantity'   => $quantities[$p->id] ?? 1,
            ]))->setRelation('product', $p))
            ->values();

        $build = new Build();
        $build->setRelation('items', $items);

        return $build;
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
