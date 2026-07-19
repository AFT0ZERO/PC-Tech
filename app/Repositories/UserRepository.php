<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function paginateWithSearchAndSort(?string $search = null, ?string $role = null, string $sort = 'created_desc', int $perPage = 15)
    {
        $query = User::query();

        if (!empty($search)) {
            $s = $search;
            $query->where(function ($q) use ($s) {
                $q->where('fname', 'like', '%' . $s . '%')
                    ->orWhere('lname', 'like', '%' . $s . '%')
                    ->orWhere('email', 'like', '%' . $s . '%');
            });
        }

        if (!empty($role)) {
            $query->where('role', $role);
        }

        match ($sort) {
            'name_asc' => $query->orderBy('fname')->orderBy('lname'),
            'name_desc' => $query->orderBy('fname', 'desc')->orderBy('lname', 'desc'),
            'role_asc' => $query->orderBy('role')->orderBy('fname'),
            'role_desc' => $query->orderBy('role', 'desc')->orderBy('fname'),
            'created_asc' => $query->orderBy('created_at', 'asc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        return $query->paginate($perPage)->withQueryString();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function save(User $user): bool
    {
        return $user->save();
    }

    public function delete(User $user): ?bool
    {
        return $user->delete();
    }

    public function findWithTrashed(int $id): ?User
    {
        return User::withTrashed()->find($id);
    }

    public function restore(User $user): bool
    {
        return $user->restore();
    }

    public function onlyTrashedPaginate(int $perPage = 15)
    {
        return User::onlyTrashed()->paginate($perPage);
    }
}
