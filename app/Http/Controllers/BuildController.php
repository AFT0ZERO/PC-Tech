<?php

namespace App\Http\Controllers;

use App\Models\Build;
use App\Models\BuildPart;
use App\Models\Category;
use App\Models\Product;
use App\Services\BuildCompatibilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BuildController extends Controller
{
    public function __construct(private BuildCompatibilityService $compatibility)
    {
    }

    // ── GET /builder ─────────────────────────────────────────────────────────
    public function index()
    {
        $categories = Category::all();

        $slotNames = ['CPU', 'Motherboard', 'RAM', 'GPU', 'Storage', 'PSU', 'Cooler', 'Case'];

        $builderCategories = Category::whereIn(
            DB::raw('LOWER(name)'),
            array_map('strtolower', $slotNames)
        )->get()->sortBy(fn ($c) => array_search(strtolower($c->name), array_map('strtolower', $slotNames)));

        return view('builder.index', compact('categories', 'builderCategories'));
    }

    // ── GET /builder/parts/{category} ────────────────────────────────────────
    public function getParts(Category $category)
    {
        $products = Product::with('stores')
            ->select(
                'products.*',
                DB::raw('(SELECT MIN(product_price) FROM store_product WHERE store_product.product_id = products.id) as cheapest_price')
            )
            ->where('category_id', $category->id)
            ->orderBy('name')
            ->get()
            ->map(fn ($p) => [
                'id'             => $p->id,
                'name'           => $p->name,
                'brand'          => $p->brand,
                'cheapest_price' => (float) ($p->cheapest_price ?? 0),
                'category_name'  => $p->category->name ?? '',
            ]);

        return response()->json($products);
    }

    // ── POST /builder/check-compatibility ────────────────────────────────────
    public function checkCompatibility(Request $request)
    {
        $request->validate([
            'part_ids'   => 'array',
            'part_ids.*' => 'integer|exists:products,id',
        ]);

        $warnings = $this->compatibility->check($request->input('part_ids', []));

        return response()->json(['warnings' => $warnings]);
    }

    // ── POST /builder/save ───────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:150',
            'notes'      => 'nullable|string',
            'part_ids'   => 'required|array|min:1',
            'part_ids.*' => 'integer|exists:products,id',
        ]);

        $partIds = $request->input('part_ids');

        $totalPrice = Product::select(
                'products.id',
                DB::raw('(SELECT MIN(product_price) FROM store_product WHERE store_product.product_id = products.id) as cheapest_price')
            )
            ->whereIn('products.id', $partIds)
            ->get()
            ->sum(fn ($p) => (float) ($p->cheapest_price ?? 0));

        DB::transaction(function () use ($request, $partIds, $totalPrice) {
            $build = Build::create([
                'user_id'     => Auth::id(),
                'name'        => $request->input('name'),
                'notes'       => $request->input('notes'),
                'total_price' => $totalPrice,
            ]);

            $products = Product::with('category')->whereIn('id', $partIds)->get()->keyBy('id');

            foreach ($partIds as $productId) {
                $product = $products->get($productId);
                if ($product) {
                    BuildPart::create([
                        'build_id'      => $build->id,
                        'product_id'    => $productId,
                        'category_name' => $product->category->name ?? 'Unknown',
                    ]);
                }
            }
        });

        return response()->json(['success' => true, 'message' => 'Build saved successfully!']);
    }

    // ── GET /builder/my-builds ───────────────────────────────────────────────
    public function myBuilds()
    {
        $categories = Category::all();

        $builds = Build::with(['products' => fn ($q) => $q->withPivot('category_name')])
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return view('builder.my-builds', compact('categories', 'builds'));
    }

    // ── DELETE /builder/{build} ──────────────────────────────────────────────
    public function destroy(Build $build)
    {
        $this->authorize('delete', $build);

        $build->delete();

        return response()->json(['success' => true, 'message' => 'Build deleted successfully.']);
    }
}
