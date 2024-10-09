<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{

    public function index(Request $request)
    {
        $Store_query = Store::query();
        $search_param = $request->query('search');
        if (!empty($search_param)) {
            $Store_query = Store::search($search_param);
        }
        $StoreFromDB = $Store_query->paginate(15);

        return view('admin.store.index',['stores'=>$StoreFromDB]);
    }


    public function create()
    {
        return view('admin.store.create');
    }


    public function store(Request $request)
    {
        request()->validate(
            [
                'name'=>['required','min:3'],
                'image'=>['nullable','image','mimes:jpeg,png,jpg'],
            ]
        );
        if ($request->hasFile('image')) {
            $file=$request->file('image');
            $extension=$file->getClientOriginalExtension();
            $fileName=time().'.'.$extension;
            $path='uploads/store/';
            $file->move($path, $fileName);
        }

        $name = request()->name;


        Store::create([
            'name'=>$name,
            'image'=>'uploads/store/'.$fileName
        ]);
        session()->flash('success', 'Store Created Successfully!');
        return to_route('store.index');
    }


    public function show(Store $store)
    {
        return view("admin.store.show", ["store" => $store]);
    }

    public function edit(Store $store)
    {
        return view('admin.store.edit', ['store' => $store]);
    }


    public function update(Request $request, Store $store)
    {
        request()->validate(
            [
                'name'=>['required','min:3'],
                'image'=>['nullable','image','mimes:jpeg,png,jpg'],
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

        return to_route('store.show', $store->id);
    }


    public function destroy(Store $store)
    {
        $store->delete();
        session()->flash('success', 'Store Deleted Successfully!');
        return to_route('store.index');
    }

    public function restore( $id)
    {
        $store = Store::withTrashed()->find($id);
        $store->restore();
        session()->flash('success', 'Store Restore Successfully!');
        return to_route('store.showRestore');
    }

    public function showRestore( )
    {
        $store = Store::onlyTrashed()->paginate(15);
        return view('admin.store.restore' , ['stores' => $store]);
    }
}
