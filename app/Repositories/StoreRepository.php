<?php

namespace App\Repositories;

use App\Models\Store;

class StoreRepository
{
    public function paginateWithSearchAndSort(?string $search = null, string $sort = 'name_asc', int $perPage = 15)
    {
        $query = Store::query();

        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        match ($sort) {
            'name_desc' => $query->orderBy('name', 'desc'),
            'created_asc' => $query->orderBy('created_at', 'asc'),
            'created_desc' => $query->orderBy('created_at', 'desc'),
            default => $query->orderBy('name', 'asc'),
        };

        return $query->paginate($perPage);
    }

    public function create(array $data): Store
    {
        return Store::create($data);
    }

    public function save(Store $store): bool
    {
        return $store->save();
    }

    public function delete(Store $store): ?bool
    {
        return $store->delete();
    }

    public function findWithTrashed(int $id): ?Store
    {
        return Store::withTrashed()->find($id);
    }

    public function restore(Store $store): bool
    {
        return $store->restore();
    }

    public function onlyTrashedPaginate(int $perPage = 15)
    {
        return Store::onlyTrashed()->paginate($perPage);
    }

    public function all()
    {
        return Store::all();
    }
}
