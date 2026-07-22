<?php

namespace App\Http\Controllers;

use App\Http\Requests\AutocompleteRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductImageService;
use App\Services\ProductService;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private ProductImageService $productImageService,
    ) {
    }

    public function index()
    {
        $search = request('search');
        $categoryId = request('category_id');
        $sort = request('sort', 'name_asc');

        return view('admin.product.index', $this->productService->getIndexData($search, $categoryId, $sort));
    }

    public function create()
    {
        return view('admin.product.create', $this->productService->getCreateData());
    }

    public function fields(Category $category)
    {
        return response()->json($this->productService->resolveFields($category));
    }

    public function autocomplete(AutocompleteRequest $request)
    {
        return response()->json(
            $this->productService->autocomplete($request->input('query'), $request->category_id)
        );
    }

    public function store(StoreProductRequest $request)
    {
        $product = $this->productService->storeProduct($request->validated());

        if ($request->hasFile('images')) {
            $this->productImageService->uploadImages($product->id, $request->file('images'));
        }

        $this->productService->syncScraperConfig();

        return to_route('product.index')->with('success', 'Product stored successfully!');
    }

    public function show(Product $product)
    {
        return view('admin.product.show', $this->productService->getShowData($product));
    }

    public function edit(Product $product)
    {
        return view('admin.product.edit', $this->productService->getEditData($product));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
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
        foreach ($newStoreIds as $i => $newStoreId) {
            $newStores[] = [
                'store_id' => $newStoreId,
                'price'    => $request->input("new_price.$i", 0),
                'url'      => $request->input("new_url.$i", ''),
                'status'   => $request->input("new_status.$i", 'out of stock'),
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
        $this->productService->restoreProduct($id);
        session()->flash('success', 'Product Restore Successfully!');

        return to_route('product.showRestore');
    }

    public function showRestore()
    {
        return view('admin.product.restore', [
            'products' => $this->productService->getTrashed(),
        ]);
    }
}
