<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $query = Store::query();

        $searchParam = $request->query('search');
        if (!empty($searchParam)) {
            $query->where('name', 'like', '%' . $searchParam . '%');
        }

        $sort = $request->query('sort', 'name_asc');
        match ($sort) {
            'name_desc' => $query->orderBy('name', 'desc'),
            'created_asc' => $query->orderBy('created_at', 'asc'),
            'created_desc' => $query->orderBy('created_at', 'desc'),
            default => $query->orderBy('name', 'asc'),
        };

        $stores = $query->paginate(15);

        return view('admin.store.index', ['stores' => $stores]);
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => ['required', 'min:3'],
                'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg'],
            ]
        );

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;
            $path = 'uploads/store/';
            $file->move($path, $fileName);
            $imagePath = $path . $fileName;
        }

        Store::create([
            'name' => $request->name,
            'image' => $imagePath,
        ]);
        session()->flash('success', 'Store Created Successfully!');

        return back();
    }

    public function update(Request $request, Store $store)
    {
        $request->validate(
            [
                'name' => ['required', 'min:3'],
                'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg'],
            ]
        );

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;
            $path = 'uploads/store/';
            $file->move($path, $fileName);
            $store->image = $path . $fileName;
        }

        $store->name = $request->name;
        $store->save();

        session()->flash('success', 'Store updated successfully!');

        return back();
    }

    public function destroy(Store $store)
    {
        $store->delete();
        session()->flash('success', 'Store Deleted Successfully!');

        return back();
    }

    public function restore($id)
    {
        $store = Store::withTrashed()->find($id);
        $store->restore();
        session()->flash('success', 'Store Restore Successfully!');

        return to_route('store.showRestore');
    }

    public function showRestore()
    {
        $store = Store::onlyTrashed()->paginate(15);

        return view('admin.store.restore', ['stores' => $store]);
    }
}
