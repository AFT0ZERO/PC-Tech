<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\PriceHistory;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $query = Product::query()->with('category');
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', '%' . $s . '%')
                    ->orWhere('brand', 'like', '%' . $s . '%');
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $sort = $request->query('sort', 'name_asc');
        match ($sort) {
            'name_desc' => $query->orderBy('name', 'desc'),
            'created_asc' => $query->orderBy('created_at', 'asc'),
            'created_desc' => $query->orderBy('created_at', 'desc'),
            default => $query->orderBy('name', 'asc'),
        };

        $products = $query->paginate(25)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.product.index', ['products' => $products, 'categories' => $categories]);
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
            'description' => 'required',
            'brand'=>'required',
            'category' => 'required|exists:categories,id',
            'key' => 'required|array',
            'value' => 'required|array',
            'price' => 'required|array',
            'url' => 'required|array',
            'price.*' => 'required|numeric',
            'url.*' => 'required|string',
        ]);

        // Step 1: Create the Product
        $product = Product::create([
            'name' => $request->name,
            'smallDescription' => $request->description,
            'brand' => $request->brand,
            'category_id' => $request->category,
            'description' => json_encode(array_combine($request->key, $request->value)) 
        ]);

        // Step 2: Attach stores with prices and URLs
        $stores = $request->store_id;
        $prices = $request->price;
        $urls = $request->url;
        $status = $request->status;

        foreach ($stores as $index => $storeId) {
            $product->stores()->attach($storeId, [
                'product_price' => $prices[$index],
                'product_url' => $urls[$index],
                'product_status' => $status[$index]
            ]);
        }

        $this->syncScraperConfig();

        return to_route('product.index')->with('success', 'Product stored successfully!');
    }

    public function show(Product $product)
    {
        $product->load(['images', 'stores', 'category']);

        $description = json_decode($product->description, true);

        $allHistory = PriceHistory::join('store_product', 'price_history.sp_id', '=', 'store_product.id')
            ->join('stores', 'store_product.store_id', '=', 'stores.id')
            ->where('store_product.product_id', $product->id)
            ->where('price_history.status', 'ok')
            ->orderBy('price_history.scraped_at', 'asc')
            ->select('price_history.*', 'stores.name as store_name', 'store_product.store_id', 'store_product.product_url as store_url')
            ->get();
        $priceHistory = $allHistory->groupBy('store_name')->map(fn($rows) => $rows->last());

        return view("admin.product.show", [
            "product"      => $product,
            'descriptions' => $description,
            'priceHistory' => $priceHistory,
            'allHistory'   => $allHistory,
        ]);
    }

    public function edit(Product $product)
    {
        $CategoryFromDB = Category::all();
        $StoreFromDB = Store::all();
        $product->load('stores');
        $description = json_decode($product->description, true);
        return view('admin.product.edit', ['categories' => $CategoryFromDB, 'stores' => $StoreFromDB, 'product' => $product, 'descriptions' => $description]);
    }

    public function update(Request $request, Product $product)
    {
        // Validate the incoming request
        $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'required',
            'brand'          => 'required',
            'category'       => 'required|exists:categories,id',
            'key.*'          => 'required|string',
            'value.*'        => 'required|string',
            'price.*.*'      => 'nullable|numeric',
            'url.*.*'        => 'nullable|string',
            'new_store_id.*' => 'nullable|exists:stores,id',
            'new_price.*'    => 'nullable|numeric',
            'new_url.*'      => 'nullable|string',
            'new_status.*'   => 'nullable|string',
        ]);

        // Update product details
        $product->name = $request->name;
        $product->brand = $request->brand;
        $product->smallDescription = $request->description;
        $product->category_id = $request->category;
        $product->save();

        // Update product specifications 
        $descriptions = array_combine($request->input('key'), $request->input('value'));
        $product->description = json_encode($descriptions);
        $product->save();

        // Update existing store-specific product details in the pivot table
        foreach ($request->input('store_id', []) as $storeId) {
            $prices = $request->input("price.$storeId", []);
            $urls   = $request->input("url.$storeId", []);
            $status = $request->input("status.$storeId", []);

            if (!empty($prices)) {
                $product->stores()->updateExistingPivot($storeId, [
                    'product_price'  => $prices[0],
                    'product_url'    => $urls[0]   ?? '',
                    'product_status' => $status[0] ?? 'out of stock',
                ]);
            }
        }

        // Attach newly added stores
        $newStoreIds = $request->input('new_store_id', []);
        $newPrices   = $request->input('new_price', []);
        $newUrls     = $request->input('new_url', []);
        $newStatuses = $request->input('new_status', []);

        foreach ($newStoreIds as $i => $newStoreId) {
            if (empty($newStoreId)) continue;
            // Only attach if not already attached
            if (!$product->stores()->where('store_id', $newStoreId)->exists()) {
                $product->stores()->attach($newStoreId, [
                    'product_price'  => $newPrices[$i]   ?? 0,
                    'product_url'    => $newUrls[$i]     ?? '',
                    'product_status' => $newStatuses[$i] ?? 'out of stock',
                ]);
            }
        }
        $this->syncScraperConfig();
        // Redirect back with a success message
        return back()->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        $this->syncScraperConfig();
        session()->flash('success', 'Product Deleted Successfully!');
        return back();
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

    public function fetchSpecs(Request $request)
    {
        $request->validate([
            'query' => 'required|string|max:255',
        ]);

        $query   = $request->input('query');
        $dbPath  = base_path('scraper/components.sqlite');

        if (!file_exists($dbPath)) {
            return response()->json(['error' => 'Components database not found']);
        }

        try {
            $rawData = $this->getBestMatch($query, $dbPath);

            if (!$rawData) {
                return response()->json(['error' => 'No results found in database']);
            }

            return response()->json([
                'name'  => $rawData['metadata']['name'] ?? '',
                'url'   => '',
                'specs' => $this->mapSpecs($rawData),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    private function getBestMatch(string $query, string $dbPath): ?array
    {
        $pdo = new \PDO("sqlite:$dbPath");
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        // Exact match
        $stmt = $pdo->prepare("SELECT name, specs_json FROM components WHERE name = ?");
        $stmt->execute([$query]);
        $exact = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($exact) {
            return json_decode($exact['specs_json'], true);
        }

        // LIKE search
        $stmt = $pdo->prepare("SELECT name, specs_json FROM components WHERE search_text LIKE ?");
        $stmt->execute(["%$query%"]);
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Word-by-word fallback
        if (empty($results)) {
            $words = explode(' ', $query);
            if (\count($words) > 1) {
                $stmt->execute(['%' . implode('%', $words) . '%']);
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            }
        }

        if (empty($results)) {
            return null;
        }

        // Fuzzy match
        $bestScore = 0;
        $bestData  = null;
        foreach ($results as $row) {
            similar_text(strtolower($query), strtolower($row['name']), $percent);
            if ($percent > $bestScore) {
                $bestScore = $percent;
                $bestData  = json_decode($row['specs_json'], true);
            }
        }

        return $bestScore >= 30 ? $bestData : json_decode($results[0]['specs_json'], true);
    }

    private function detectComponentType(array $data): string
    {
        if (isset($data['socket'], $data['chipset']))        return 'motherboard';
        if (isset($data['socket']))                          return 'cpu';
        if (isset($data['chipset_manufacturer']))            return 'gpu';
        if (isset($data['ram_type']) || isset($data['memory_type']) && !isset($data['chipset'])) return 'ram';
        if (isset($data['storage_type']) || isset($data['nvme'])) return 'storage';
        if (isset($data['wattage']))                         return 'psu';
        return 'unknown';
    }

    private function mapSpecs(array $data): array
    {
        $meta  = $data['metadata'] ?? [];
        $type  = $this->detectComponentType($data);

        $specs = match ($type) {
            'cpu'         => [
                'Manufacturer' => $meta['manufacturer'] ?? null,
                'Series'       => $meta['series'] ?? $data['series'] ?? null,
                'Socket'       => $data['socket'] ?? null,
                'Cores'        => $data['cores']['total'] ?? null,
                'Threads'      => $data['cores']['threads'] ?? null,
                'Base Clock'   => isset($data['clocks']['performance']['base'])
                                    ? "{$data['clocks']['performance']['base']} GHz" : null,
                'Boost Clock'  => isset($data['clocks']['performance']['boost'])
                                    ? "{$data['clocks']['performance']['boost']} GHz" : null,
                'TDP'          => isset($data['specifications']['tdp'])
                                    ? "{$data['specifications']['tdp']}W"
                                    : (isset($data['tdp']) ? "{$data['tdp']}W" : null),
            ],
            'ram'         => [
                'Manufacturer' => $meta['manufacturer'] ?? null,
                'Series'       => $meta['series'] ?? null,
                'Form Factor'  => $data['form_factor'] ?? null,
                'Memory Type'  => $data['ram_type'] ?? $data['memory_type'] ?? null,
                'Capacity'     => isset($data['capacity']) ? "{$data['capacity']} GB" : null,
                'Speed'        => isset($data['speed'])    ? "{$data['speed']} MHz"   : null,
            ],
            'storage'     => [
                'Manufacturer' => $meta['manufacturer'] ?? null,
                'Series'       => $meta['series'] ?? null,
                'Form Factor'  => $data['form_factor'] ?? null,
                'Type'         => $data['storage_type'] ?? $data['type'] ?? null,
                'Capacity'     => isset($data['capacity']) ? "{$data['capacity']} GB" : null,
                'Interface'    => $data['interface'] ?? null,
            ],
            'psu'         => [
                'Manufacturer' => $meta['manufacturer'] ?? null,
                'Series'       => $meta['series'] ?? null,
                'Form Factor'  => $data['form_factor'] ?? null,
                'Wattage'      => isset($data['wattage'])          ? "{$data['wattage']}W"          : null,
                'Length'       => isset($data['length'])           ? "{$data['length']} mm"          : null,
                'Efficiency'   => $data['efficiency_rating'] ?? null,
                'Modular'      => $data['modular'] ?? null,
            ],
            'gpu'         => [
                'Manufacturer' => $meta['manufacturer'] ?? null,
                'Series'       => $meta['series'] ?? null,
                'Chipset'      => $data['chipset'] ?? null,
                'VRAM'         => isset($data['memory']) ? "{$data['memory']} GB" : null,
                'Memory Type'  => $data['memory_type'] ?? null,
                'Core Clock'   => isset($data['core_base_clock'])  ? "{$data['core_base_clock']} MHz"  : null,
                'Boost Clock'  => isset($data['core_boost_clock']) ? "{$data['core_boost_clock']} MHz" : null,
                'Memory Bus'   => !empty($data['memory_bus'])      ? "{$data['memory_bus']} bit"        : null,
                'TDP'          => isset($data['tdp'])              ? "{$data['tdp']}W"                  : null,
                'Interface'    => $data['interface'] ?? null,
            ],
            'motherboard' => [
                'Manufacturer' => $meta['manufacturer'] ?? null,
                'Series'       => $meta['series'] ?? null,
                'Socket'       => $data['socket'] ?? null,
                'Chipset'      => $data['chipset'] ?? null,
                'Form Factor'  => $data['form_factor'] ?? null,
                'RAM Slots'    => $data['memory']['slots'] ?? null,
                'Max Memory'   => isset($data['memory']['max']) ? "{$data['memory']['max']} GB" : null,
                'Memory Type'  => $data['memory']['ram_type'] ?? null,
                'PCIe Slots'   => isset($data['pcie_slots']) ? \count($data['pcie_slots']) : null,
            ],
            default       => [
                'Manufacturer' => $meta['manufacturer'] ?? null,
                'Series'       => $meta['series'] ?? null,
                'Form Factor'  => $data['form_factor'] ?? null,
            ],
        };

        return array_filter($specs);
    }

    private function syncScraperConfig()
    {
        $configPath = base_path('scraper/config.json');
        if (!file_exists($configPath)) return;
        
        $configJson = file_get_contents($configPath);
        $config = json_decode($configJson, true);
        
        if (!$config || !isset($config['stores'])) return;
        
        // Get all active store/product pairs with URLs
        $storeProducts = DB::table('store_product')
            ->join('products', 'products.id', '=', 'store_product.product_id')
            ->whereNull('products.deleted_at')
            ->select('store_product.store_id', 'store_product.product_id', 'store_product.product_url')
            ->get();
        
        $storeUrls = []; 
        foreach($storeProducts as $sp) {
            if (!empty($sp->product_url)) {
                $storeUrls[$sp->store_id][] = [
                    'part_id' => $sp->product_id,
                    'url' => $sp->product_url
                ];
            }
        }
        
        $storesFromDb = Store::all()->keyBy('name');
        
        foreach ($config['stores'] as &$configStore) {
            $storeName = $configStore['store_name'];
            if ($storesFromDb->has($storeName)) {
                $dbStore = $storesFromDb->get($storeName);
                if (isset($storeUrls[$dbStore->id])) {
                    $configStore['products'] = $storeUrls[$dbStore->id];
                } else {
                    $configStore['products'] = [];
                }
            }
        }
        
        file_put_contents($configPath, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
