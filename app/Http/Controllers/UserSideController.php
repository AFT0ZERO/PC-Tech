<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Faqs;
use App\Models\Feedback;
use App\Models\PriceHistory;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
        $product = Product::with('stores', 'images', 'category')->find($id);
        $description=json_decode($product->description, true);
        $feedbacks = Feedback::where('product_id', $product->id)->get();
        $CategoryProduct = Product::where('category_id', $product->category->id)->with('stores')
    ->select('products.*', DB::raw('(SELECT MIN(product_price) FROM store_product WHERE store_product.product_id = products.id) as cheapest_price'))
        ->paginate(15);

        // Scraped price history: all entries for this product ordered by time
        $allHistory = PriceHistory::join('store_product', 'price_history.sp_id', '=', 'store_product.id')
            ->join('stores', 'store_product.store_id', '=', 'stores.id')
            ->where('store_product.product_id', $product->id)
            ->where('price_history.status', 'ok')
            ->orderBy('price_history.scraped_at', 'asc')
            ->select('price_history.*', 'stores.name as store_name', 'store_product.store_id', 'store_product.product_url as store_url')
            ->get();

        // Latest price per store (for the price table)
        $priceHistory = $allHistory->groupBy('store_name')->map(fn($rows) => $rows->last());

        // Full history grouped by store for the chart (all data points)
        $priceHistoryChart = $allHistory->groupBy('store_name')->map(function ($rows) {
            return $rows->map(fn($r) => [
                'date'  => $r->scraped_at->format('Y-m-d H:i'),
                'price' => (float) $r->price,
            ])->values();
        });

        return view('userSide.pages.singleProduct', [
            'product'           => $product,
            'description'       => $description,
            'CategoryProducts'  => $CategoryProduct,
            'categories'        => $categories,
            'feedbacks'         => $feedbacks,
            'priceHistory'      => $priceHistory,
            'priceHistoryChart' => $priceHistoryChart,
        ]);
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

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        /** @var User $user */
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect.',
            ])->withInput();
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return to_route('account')->with('password_success', 'Password changed successfully!');
    }



}
