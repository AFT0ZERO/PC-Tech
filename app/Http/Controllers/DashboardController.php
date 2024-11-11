<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $products = Product::all()->count();
        $categories = Category::all()->count();
        $user = User::all()->count();
        $shop = Store::all()->count();


        return view('admin.dashboard.home' , ['products'=>$products,'categories'=>$categories,'users'=>$user,'shoppes'=>$shop]);
    }

}
