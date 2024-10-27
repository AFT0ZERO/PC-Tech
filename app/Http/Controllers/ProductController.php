<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $Product_query = Product::query();
        $search_param = $request->query('search');
        if (!empty($search_param)) {
            $Product_query = Product::search($search_param);
        }
        $ProductFromDB = $Product_query->paginate(15);
        return view('admin.product.index',['products'=>$ProductFromDB]);
    }


    public function create()
    {
        $CategoryFromDB=Category::all();
        $StoreFromDB=Store::all();
        return view('admin.product.create' , ['categories'=>$CategoryFromDB , 'stores'=>$StoreFromDB]);
    }


    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|exists:categories,id',
            'key' => 'required|array',
            'value' => 'required|array',
            'price' => 'required|array',
            'url' => 'required|array',
            'price.*' => 'required|numeric',
            'url.*' => 'required|url',
        ]);

        // Step 1: Create the Product
        $product = Product::create([
            'name' => $request->name,
            'category_id' => $request->category,
            'description' => json_encode(array_combine($request->key, $request->value)) // Combine key-value pairs into JSON
        ]);

        // Step 2: Attach stores with prices and URLs
        $stores = $request->store_id; // Assuming you pass store IDs from the form (for each store)
        $prices = $request->price;
        $urls = $request->url;

        foreach ($stores as $index => $storeId) {
            $product->stores()->attach($storeId, [
                'product_price' => $prices[$index],
                'product_url' => $urls[$index],
            ]);
        }



        return redirect()->back()->with('success', 'Data stored successfully!');
    }


    public function show(Product $product)
    {
        $description=json_decode($product->description, true);

        return view("admin.product.show", ["product" => $product ,'descriptions'=>$description]);
    }

    public function edit(Product $product)
    {

        $CategoryFromDB=Category::all();
        $StoreFromDB = Store::with(['products' => function ($query) use ($product) {
            $query->where('product_id', $product->id);
        }])->get();
        $description=json_decode($product->description, true);
        return view('admin.product.edit' , ['categories'=>$CategoryFromDB , 'stores'=>$StoreFromDB , 'product'=>$product , 'descriptions'=>$description]);
    }


    public function update(Request $request, Product $product)
    {
        //
    }


    public function destroy(Product $product)
    {
        $product->delete();
        session()->flash('success', 'Product Deleted Successfully!');
        return to_route('product.index');
    }

    public function restore( $id)
    {
        $product = Product::withTrashed()->find($id);
        $product->restore();
        session()->flash('success', 'Product Restore Successfully!');
        return to_route('product.showRestore');
    }

    public function showRestore( )
    {
        $product = Product::onlyTrashed()->paginate(15);
        return view('admin.product.restore' , ['products' => $product]);
    }
}
