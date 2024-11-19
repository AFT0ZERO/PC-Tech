<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Faqs;
use App\Models\Feedback;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserSideController extends Controller
{

    public function landing()
    {
        $categories = Category::all();
      $lastProduct = Product::with('stores')->select('products.*', DB::raw('(SELECT MIN(product_price) FROM store_product WHERE store_product.product_id = products.id) as cheapest_price'))
            ->orderBy('created_at', 'desc')->paginate(7);
      $CategoryProduct = Product::with('stores')->select('products.*', DB::raw('(SELECT MIN(product_price) FROM store_product WHERE store_product.product_id = products.id) as cheapest_price'))->where('category_id', 1)->paginate(7);
      return view('userSide.pages.landing' , ['lastProducts' => $lastProduct, 'CategoryProducts' => $CategoryProduct, 'categories' => $categories]);
    }

    public function category($id = null)
    {
        $category = Category::all();

        // Initialize brandsWithCounts based on conditions
        $brandsWithCountsQuery = Product::selectRaw('brand, COUNT(*) as product_count')
            ->groupBy('brand');

        // If a category is selected, filter brands by that category
        if ($id > 0) {
            $brandsWithCountsQuery->whereHas('category', function ($query) use ($id) {
                $query->where('id', $id);
            });
        }

        // Fetch the brand counts
        $brandsWithCounts = $brandsWithCountsQuery->get();

        $Product_query = Product::query();
        $search_param = request()->query('search');

        if (!empty($search_param)) {
            $Product_query = Product::search($search_param);
            $products = $Product_query->paginate(15);

        } elseif ($id == 0 || $id == null) {
            $products = Product::with('stores')
            ->select('products.*', DB::raw('(SELECT MIN(product_price) FROM store_product WHERE store_product.product_id = products.id) as cheapest_price'))
                ->paginate(15);

        } elseif ($id > 0) {
            $products = Product::with('stores')
            ->select('products.*', DB::raw('(SELECT MIN(product_price) FROM store_product WHERE store_product.product_id = products.id) as cheapest_price'))
                ->whereHas('category', function ($query) use ($id) {
                    $query->where('id', $id);
                })->paginate(15);

        }

        return view('userSide.pages.category', ['categories' => $category, 'products' => $products, 'brands' => $brandsWithCounts]);
    }

    public function singlePage($id)
    {
        $categories = Category::all();
        $product = Product::find($id);
        $description=json_decode($product->description, true);
        $feedbacks = Feedback::where('product_id', $product->id)->get();
        $CategoryProduct = Product::where('category_id', $product->category->id)->with('stores')
    ->select('products.*', DB::raw('(SELECT MIN(product_price) FROM store_product WHERE store_product.product_id = products.id) as cheapest_price'))
        ->paginate(15);

        return view('userSide.pages.singleProduct', ['product' => $product, 'description' => $description ,'CategoryProducts' => $CategoryProduct ,'categories' => $categories , 'feedbacks' => $feedbacks]);
    }


    public function about()
    {
        $categories = Category::all();
        return view('userSide.pages.about', ['categories' => $categories]);
    }
    public function contact()
    {
        $categories = Category::all();
        return view('userSide.pages.contact', ['categories' => $categories]);
    }

    public function faqs()
    {
        $categories = Category::all();
        $faqs = Faqs::all();
        return view('userSide.pages.faqs', ['categories' => $categories , 'faqs' => $faqs]);
    }

 public function account()
    {
        $categories = Category::all();
        return view('userSide.pages.userAccount', ['categories' => $categories]);
    }


    public function updateAccount(Request $request, User $user)
    {
        // Validate the input
        $request->validate([
            'fname' => ['required', 'min:3'],
            'lname' => ['required', 'min:3'],
            'email' => ['required', 'email'],
            'mobile' => ['required', 'min:9', 'numeric'],
            'gender' => ['required'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg'],
        ]);

        // If there's a new image uploaded, handle the upload process
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;
            $path = 'uploads/user/';
            $file->move($path, $fileName);

            // If image uploaded, set the new image path
            $user->image = $path . $fileName;
        }

        // Update the other user fields
        $user->fname = $request->fname;
        $user->lname = $request->lname;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->gender = $request->gender;

        // Save the updated user information
        $user->save();

        session()->flash('success', 'User updated successfully!');
        // Redirect to the user show route
        return to_route('account');
    }



}
