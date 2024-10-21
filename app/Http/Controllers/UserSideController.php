<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserSideController extends Controller
{

    public function landing()
    {
      $lastProduct = Product::orderBy('created_at', 'asc')->paginate(7);
      $CategoryProduct = Product::where('category_id', 1)->paginate(7);
      return view('userSide.pages.landing' , ['lastProducts' => $lastProduct, 'CategoryProducts' => $CategoryProduct]);
    }

    public function category()
    {
        $category = Category::all();
        $products = Product::with('stores') // Fetch the stores
        ->select('products.*', DB::raw('(SELECT MIN(product_price) FROM store_product WHERE store_product.product_id = products.id) as cheapest_price'))
            ->paginate(15);

        return view('userSide.pages.category', ['categories' => $category , 'products' => $products]);
    }
    public function singlePage($id)
    {
        $product = Product::find($id);
        $description=json_decode($product->description, true);
        $CategoryProduct = Product::where('category_id', 1)->paginate(7);

        return view('userSide.pages.singleProduct', ['product' => $product, 'description' => $description ,'CategoryProducts' => $CategoryProduct]);
    }




}
