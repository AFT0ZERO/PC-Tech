<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

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
        return view('userSide.pages.category');
    }
    public function singlePage($id)
    {
        $product = Product::find($id);
        $description=json_decode($product->description, true);
        $CategoryProduct = Product::where('category_id', 1)->paginate(7);

        return view('userSide.pages.singleProduct', ['product' => $product, 'description' => $description ,'CategoryProducts' => $CategoryProduct]);
    }




}
