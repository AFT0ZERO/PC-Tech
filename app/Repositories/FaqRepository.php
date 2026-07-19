<?php

namespace App\Repositories;

use App\Models\Faqs;

class FaqRepository
{
    public function paginateWithSearch(?string $search = null, int $perPage = 15)
    {
        if (!empty($search)) {
            return Faqs::search($search)->paginate($perPage);
        }
        return Faqs::query()->paginate($perPage);
    }

    public function create(array $data): Faqs
    {
        return Faqs::create($data);
    }

    public function update(Faqs $faq, array $data): bool
    {
        return $faq->update($data);
    }

    public function delete(Faqs $faq): ?bool
    {
        return $faq->delete();
    }

    public function findWithTrashed(int $id): ?Faqs
    {
        return Faqs::withTrashed()->find($id);
    }

    public function restore(Faqs $faq): bool
    {
        return $faq->restore();
    }

    public function onlyTrashedPaginate(int $perPage = 15)
    {
        return Faqs::onlyTrashed()->paginate($perPage);
    }

    public function all()
    {
        return Faqs::all();
    }
}
