<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Repositories\ComponentSpecReader;
use App\Services\FormFieldResolver;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private ?ProductService $productService = null,
        private ?FormFieldResolver $fieldResolver = null,
        private ?ComponentSpecReader $componentSpecReader = null,
    ) {
        $this->fieldResolver ??= new FormFieldResolver;
        $this->componentSpecReader ??= new ComponentSpecReader;
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
        return view('admin.product.create', [
            'categories' => Category::orderBy('name')->get(),
            'stores'     => $this->productService->getStores(),
        ]);
    }

    public function fields(Category $category)
    {
        return response()->json($this->fieldResolver->resolve($category));
    }

    public function autocomplete(Request $request)
    {
        $request->validate([
            'query'       => 'required|string|min:1|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        $category = Category::findOrFail($request->category_id);

        if (empty($category->open_db_name)) {
            return response()->json(['enabled' => false, 'results' => []]);
        }

        $dbPath = base_path('scraper/components.sqlite');

        if (!file_exists($dbPath)) {
            return response()->json(['enabled' => false, 'results' => []]);
        }

        try {
            $rows = $this->componentSpecReader->searchAutocomplete(
                $request->input('query'),
                $category->open_db_name,
                $dbPath
            );

            $results = array_map(function ($row) {
                return [
                    'name'  => $row['name'],
                    'specs' => json_decode($row['specs_json'], true),
                ];
            }, $rows);

            return response()->json([
                'enabled' => true,
                'results' => $results,
            ]);
        } catch (\Exception $e) {
            return response()->json(['enabled' => false, 'results' => [], 'error' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        $category = Category::findOrFail($request->category);
        $fields = $this->fieldResolver->resolve($category);

        $rules = $this->buildValidationRules($fields);

        $request->validate($rules);

        $productData = ['category_id' => $category->id];
        foreach ($fields['product_fields'] as $field) {
            $productData[$field['name']] = $request->input($field['name']);
        }

        if ($request->has('key') && $request->has('value')) {
            $productData['description'] = json_encode(array_combine($request->key, $request->value));
        }

        $specData = null;
        $specsTable = $category->specs_table;

        if ($specsTable && !empty($fields['spec_fields'])) {
            $specData = [];
            foreach ($fields['spec_fields'] as $field) {
                $specData[$field['name']] = $request->input($field['name']);
            }
        }

        $this->productService->create($productData, $this->buildStoreInputs($request), $specsTable, $specData);
        $this->productService->syncScraperConfig();

        return to_route('product.index')->with('success', 'Product stored successfully!');
    }

    public function show(Product $product)
    {
        $data = $this->productService->getShowData($product);

        return view('admin.product.show', $data);
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
            'name'             => $request->name,
            'brand'            => $request->brand,
            'smallDescription' => $request->description,
            'category_id'      => $request->category,
            'description'      => array_combine($request->input('key'), $request->input('value')),
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

    private function buildValidationRules(array $fields): array
    {
        $rules = [
            'category' => ['required', 'exists:categories,id'],
        ];

        foreach ($fields['product_fields'] as $field) {
            $rules[$field['name']] = $this->fieldValidation($field);
        }

        foreach ($fields['spec_fields'] as $field) {
            $rules[$field['name']] = $this->fieldValidation($field);
        }

        $rules['price'] = ['required', 'array'];
        $rules['url'] = ['required', 'array'];
        $rules['price.*'] = ['required', 'numeric'];
        $rules['url.*'] = ['required', 'string'];
        $rules['key'] = ['required', 'array'];
        $rules['value'] = ['required', 'array'];
        $rules['key.*'] = ['required', 'string'];
        $rules['value.*'] = ['required', 'string'];

        return $rules;
    }

    private function fieldValidation(array $field): array
    {
        $rule = $field['required'] ? ['required'] : ['nullable'];

        if ($field['type'] === 'number') {
            $rule[] = 'numeric';
        } elseif ($field['type'] === 'textarea') {
            $rule[] = 'string';
        } else {
            $rule[] = 'string';
            $rule[] = 'max:255';
        }

        return $rule;
    }

    private function buildStoreInputs(Request $request): array
    {
        $storeInputs = [];
        $stores = $request->store_id;
        $prices = $request->price;
        $urls = $request->url;
        $status = $request->status;

        foreach ($stores as $index => $storeId) {
            $storeInputs[] = [
                'store_id' => $storeId,
                'price'    => $prices[$index],
                'url'      => $urls[$index],
                'status'   => $status[$index],
            ];
        }

        return $storeInputs;
    }
}
