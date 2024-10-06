<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $Category_query = Category::query();
        $search_param = $request->query('search');
        if (!empty($search_param)) {
            $Category_query = Category::search($search_param);
        }
        $CategoryFromDB = $Category_query->paginate(15);

        return view('admin.category.index',['categories'=>$CategoryFromDB]);
    }


    public function create()
    {
        return view('admin.category.create');
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
            $path='uploads/category/';
            $file->move($path, $fileName);
        }

        $name = request()->name;


        Category::create([
            'name'=>$name,
            'image'=>'uploads/category/'.$fileName
        ]);
        session()->flash('success', 'Category Created Successfully!');
        return to_route('category.index');
    }


    public function show(Category $category)
    {
        return view("admin.category.show", ["category" => $category]);
    }


    public function edit(Category $category)
    {
        return view('admin.category.edit', ['category' => $category]);
    }


    public function update(Request $request,Category $category)
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
            $path = 'uploads/category/';
            $file->move($path, $fileName);

            // If image uploaded, set the new image path
            $category->image = $path . $fileName;
        }

        // Update the other user fields
        $category->name = $request->name;


        // Save the updated user information
        $category->save();

        session()->flash('success', 'Category updated successfully!');
        // Redirect to the user show route
        return to_route('category.show', $category->id);
    }


    public function destroy(Category $category)
    {
        $category->delete();
        session()->flash('success', 'Category Deleted Successfully!');
        return to_route('category.index');
    }

    public function restore( $id)
    {
        $category = Category::withTrashed()->find($id);
        $category->restore();
        session()->flash('success', 'Category Restore Successfully!');
        return to_route('category.showRestore');
    }

    public function showRestore( )
    {
        $category = Category::onlyTrashed()->paginate(15);
        return view('admin.category.restore' , ['categories' => $category]);
    }
}
