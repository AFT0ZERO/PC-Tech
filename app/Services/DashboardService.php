<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Feedback;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getDashboardData(): array
    {
        $products   = Product::count();
        $categories = Category::count();
        $user       = User::count();
        $shop       = Store::count();

        $categoryNames  = Category::withCount('products')->get()->pluck('name');
        $categorycounts = Category::withCount('products')->get()->pluck('products_count');

        $userMonths = collect();
        $userCounts = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $userMonths->push($date->format('M Y'));
            $userCounts->push(
                User::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count()
            );
        }

        $ratingsRaw = Feedback::select('rate', DB::raw('count(*) as total'))
            ->groupBy('rate')
            ->orderBy('rate')
            ->pluck('total', 'rate');
        $ratingsData = collect([1, 2, 3, 4, 5])->map(fn($r) => $ratingsRaw[$r] ?? 0);

        $topProducts = Feedback::select('product_id', DB::raw('AVG(rate) as avg_rate'), DB::raw('count(*) as total'))
            ->groupBy('product_id')
            ->orderByDesc('avg_rate')
            ->limit(6)
            ->with('product:id,name')
            ->get();
        $topProductNames   = $topProducts->map(fn($f) => $f->product?->name ?? 'Unknown');
        $topProductRatings = $topProducts->map(fn($f) => round($f->avg_rate, 2));

        return compact(
            'products', 'categories', 'user', 'shop',
            'categoryNames', 'categorycounts',
            'userMonths', 'userCounts',
            'ratingsData',
            'topProductNames', 'topProductRatings'
        );
    }
}
