<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use App\Services\SpecMappingService;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    public function __construct(
        private ?ProductService $productService = null,
        private ?SpecMappingService $specMappingService = null,
    ) {
        $this->specMappingService ??= new SpecMappingService(new \App\Repositories\ComponentSpecReader);
    }

    public function index(Request $request)
    {
        $search = $request->search;
        $categoryId = $request->category_id;
        $sort = $request->query('sort', 'name_asc');

        $products = $this->productService->list($search, $categoryId, $sort);
        $categories = Category::orderBy('name')->get();

        return view('admin.product.index', ['products' => $products, 'categories' => $categories]);
    }

    public function create()
    {
        $formData = $this->productService->getFormData();
        return view('admin.product.create', $formData);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
            'brand'=>'required',
            'category' => 'required|exists:categories,id',
            'key' => 'required|array',
            'value' => 'required|array',
            'price' => 'required|array',
            'url' => 'required|array',
            'price.*' => 'required|numeric',
            'url.*' => 'required|string',
        ]);

        $storeInputs = [];
        $stores = $request->store_id;
        $prices = $request->price;
        $urls = $request->url;
        $status = $request->status;

        foreach ($stores as $index => $storeId) {
            $storeInputs[] = [
                'store_id' => $storeId,
                'price' => $prices[$index],
                'url' => $urls[$index],
                'status' => $status[$index],
            ];
        }

        $this->productService->create(
            [
                'name' => $request->name,
                'smallDescription' => $request->description,
                'brand' => $request->brand,
                'category_id' => $request->category,
                'description' => json_encode(array_combine($request->key, $request->value)),
            ],
            $storeInputs
        );

        $this->productService->syncScraperConfig();

        return to_route('product.index')->with('success', 'Product stored successfully!');
    }

    public function show(Product $product)
    {
        $data = $this->productService->getShowData($product);

        return view("admin.product.show", $data);
    }

    public function edit(Product $product)
    {
        $data = $this->productService->getEditData($product);

        return view('admin.product.edit', $data);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'required',
            'brand'          => 'required',
            'category'       => 'required|exists:categories,id',
            'key.*'          => 'required|string',
            'value.*'        => 'required|string',
            'price.*.*'      => 'nullable|numeric',
            'url.*.*'        => 'nullable|string',
            'new_store_id.*' => 'nullable|exists:stores,id',
            'new_price.*'    => 'nullable|numeric',
            'new_url.*'      => 'nullable|string',
            'new_status.*'   => 'nullable|string',
        ]);

        $this->productService->update($product, [
            'name'          => $request->name,
            'brand'         => $request->brand,
            'smallDescription' => $request->description,
            'category_id'   => $request->category,
            'description'   => array_combine($request->input('key'), $request->input('value')),
        ]);

        $storeUpdates = [];
        foreach ($request->input('store_id', []) as $storeId) {
            $storeUpdates[$storeId] = [
                'prices' => $request->input("price.$storeId", []),
                'urls'   => $request->input("url.$storeId", []),
                'status' => $request->input("status.$storeId", []),
            ];
        }
        $this->productService->updateExistingStorePivot($product, $storeUpdates);

        $newStores = [];
        $newStoreIds = $request->input('new_store_id', []);
        $newPrices   = $request->input('new_price', []);
        $newUrls     = $request->input('new_url', []);
        $newStatuses = $request->input('new_status', []);

        foreach ($newStoreIds as $i => $newStoreId) {
            $newStores[] = [
                'store_id' => $newStoreId,
                'price'    => $newPrices[$i]   ?? 0,
                'url'      => $newUrls[$i]     ?? '',
                'status'   => $newStatuses[$i] ?? 'out of stock',
            ];
        }
        $this->productService->attachNewStores($product, $newStores);

        $this->productService->syncScraperConfig();

        return back()->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->productService->delete($product);
        $this->productService->syncScraperConfig();
        session()->flash('success', 'Product Deleted Successfully!');
        return back();
    }

    public function restore($id)
    {
        $product = Product::withTrashed()->find($id);
        $product->restore();
        session()->flash('success', 'Product Restore Successfully!');
        return to_route('product.showRestore');
    }

    public function showRestore()
    {
        $product = Product::onlyTrashed()->paginate(15);
        return view('admin.product.restore', ['products' => $product]);
    }

    public function fetchSpecs(Request $request)
    {
        $request->validate([
            'query' => 'required|string|max:255',
        ]);

        $query   = $request->input('query');
        $dbPath  = base_path('scraper/components.sqlite');

        if (!file_exists($dbPath)) {
            return response()->json(['error' => 'Components database not found']);
        }

        try {
            $rawData = $this->getBestMatch($query, $dbPath);

            if (!$rawData) {
                return response()->json(['error' => 'No results found in database']);
            }

            return response()->json([
                'name'  => $rawData['metadata']['name'] ?? '',
                'url'   => '',
                'specs' => $this->mapSpecs($rawData),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    private function getBestMatch(string $query, string $dbPath): ?array
    {
        return $this->specMappingService->getBestMatch($query, $dbPath);
    }

    private function detectComponentType(array $data): string
    {
        return $this->specMappingService->detectComponentType($data);
    }

    private function mapSpecs(array $data): array
    {
        return $this->specMappingService->mapSpecs($data);
    }


}
