<?php

namespace App\Repositories;

use App\Models\Contact;

class ContactRepository
{
    public function paginateWithSearch(?string $search = null, int $perPage = 15)
    {
        $query = Contact::query();
        if (!empty($search)) {
            return Contact::search($search)->paginate($perPage);
        }
        return $query->paginate($perPage);
    }

    public function create(array $data): Contact
    {
        return Contact::create($data);
    }

    public function delete(Contact $contact): ?bool
    {
        return $contact->delete();
    }

    public function findWithTrashed(int $id): ?Contact
    {
        return Contact::withTrashed()->find($id);
    }

    public function restore(Contact $contact): bool
    {
        return $contact->restore();
    }

    public function onlyTrashedPaginate(int $perPage = 15)
    {
        return Contact::onlyTrashed()->paginate($perPage);
    }
}
