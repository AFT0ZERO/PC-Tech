<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStoreRequest;
use App\Models\Store;
use App\Services\StoreService;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __construct(private StoreService $storeService)
    {
    }

    public function index(Request $request)
    {
        $searchParam = $request->query('search');
        $sort = $request->query('sort', 'name_asc');
        $stores = $this->storeService->list($searchParam, $sort);

        return view('admin.store.index', ['stores' => $stores]);
    }

    public function store(StoreStoreRequest $request)
    {
        $this->storeService->create(
            $request->safe()->only('name'),
            $request->file('image')
        );

        session()->flash('success', 'Store Created Successfully!');

        return back();
    }

    public function update(StoreStoreRequest $request, Store $store)
    {
        $this->storeService->update(
            $store,
            $request->safe()->only('name'),
            $request->file('image')
        );

        session()->flash('success', 'Store updated successfully!');

        return back();
    }

    public function destroy(Store $store)
    {
        $this->storeService->delete($store);
        session()->flash('success', 'Store Deleted Successfully!');

        return back();
    }

    public function restore($id)
    {
        $this->storeService->restore($id);
        session()->flash('success', 'Store Restore Successfully!');

        return to_route('store.showRestore');
    }

    public function showRestore()
    {
        $stores = $this->storeService->listTrashed();

        return view('admin.store.restore', ['stores' => $stores]);
    }
}
