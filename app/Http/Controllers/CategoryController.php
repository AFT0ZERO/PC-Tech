<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::query()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(15)->withQueryString();

        return view('admin.category.index', ['categories' => $categories]);
    }

    public function store(Request $request)
    {
        request()->validate(
            [
                'name' => ['required', 'min:3'],
                'image' => ['required', 'image', 'mimes:jpeg,png,jpg'],
            ]
        );
        $fileName = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;
            $path = 'uploads/category/';
            $file->move($path, $fileName);
        }

        Category::create([
            'name' => $request->name,
            'image' => 'uploads/category/' . $fileName,
        ]);
        session()->flash('success', 'Category Created Successfully!');

        return back();
    }

    public function update(Request $request, Category $category)
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
            $path = 'uploads/category/';
            $file->move($path, $fileName);
            $category->image = $path . $fileName;
        }

        $category->name = $request->name;
        $category->save();

        session()->flash('success', 'Category updated successfully!');

        return back();
    }

    public function destroy(Category $category)
    {
        $category->delete();
        session()->flash('success', 'Category Deleted Successfully!');

        return back();
    }

    public function restore($id)
    {
        $category = Category::withTrashed()->find($id);
        $category->restore();
        session()->flash('success', 'Category Restore Successfully!');

        return to_route('category.showRestore');
    }

    public function showRestore()
    {
        $category = Category::onlyTrashed()->paginate(15);

        return view('admin.category.restore', ['categories' => $category]);
    }
}
