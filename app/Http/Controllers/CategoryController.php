<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(private CategoryService $categoryService)
    {
    }

    public function index(Request $request)
    {
        $search_param = $request->query('search');
        $categories = $this->categoryService->list($search_param);

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

        $this->categoryService->create(
            ['name' => $request->name],
            $request->file('image')
        );

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

        $this->categoryService->update(
            $category,
            ['name' => $request->name],
            $request->file('image')
        );

        session()->flash('success', 'Category updated successfully!');

        return back();
    }

    public function destroy(Category $category)
    {
        $this->categoryService->delete($category);
        session()->flash('success', 'Category Deleted Successfully!');

        return back();
    }

    public function restore($id)
    {
        $this->categoryService->restore($id);
        session()->flash('success', 'Category Restore Successfully!');

        return to_route('category.showRestore');
    }

    public function showRestore()
    {
        $categories = $this->categoryService->listTrashed();

        return view('admin.category.restore', ['categories' => $categories]);
    }
}
