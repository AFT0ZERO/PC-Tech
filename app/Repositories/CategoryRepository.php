<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    public function paginateWithSearch(?string $search = null, int $perPage = 15)
    {
        $query = Category::query();
        if (!empty($search)) {
            $query->where('name', 'like', "%{$search}%");
        }
        return $query->orderBy('name')->paginate($perPage)->withQueryString();
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function save(Category $category): bool
    {
        return $category->save();
    }

    public function delete(Category $category): ?bool
    {
        return $category->delete();
    }

    public function findWithTrashed(int $id): ?Category
    {
        return Category::withTrashed()->find($id);
    }

    public function restore(Category $category): bool
    {
        return $category->restore();
    }

    public function onlyTrashedPaginate(int $perPage = 15)
    {
        return Category::onlyTrashed()->paginate($perPage);
    }

    public function all()
    {
        return Category::all();
    }

    public function allOrderedByName()
    {
        return Category::orderBy('name')->get();
    }
}
