<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
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
        return view('admin.product.create' , ['categories'=>$CategoryFromDB]);
    }


    public function store(Request $request)
    {
        // Validate the input
        $data = $request->validate([
            'name' => 'required|string|min:3',
            'key' => 'required|array',
            'value' => 'required|array',
        ]);

        // Combine keys and values into an associative array
        $description = array_combine($data['key'], $data['value']);

        // Save the data to the database
        Product::create([
            'name' =>request()->name,
            'category_id' => request()->category,
            'description' => json_encode($description) // Store as JSON
        ]);

        return redirect()->back()->with('success', 'Data stored successfully!');
    }


    public function show(Product $product)
    {
        return view("admin.product.show", ["product" => $product]);
    }

    public function edit(Product $product)
    {
        //
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
