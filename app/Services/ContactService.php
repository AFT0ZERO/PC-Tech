<?php

namespace App\Services;

use App\Models\Contact;
use App\Repositories\ContactRepository;

class ContactService
{
    public function __construct(private ContactRepository $contactRepository)
    {
    }

    public function list(?string $search = null)
    {
        return $this->contactRepository->paginateWithSearch($search);
    }

    public function create(array $data): Contact
    {
        return $this->contactRepository->create($data);
    }

    public function delete(Contact $contact): void
    {
        $this->contactRepository->delete($contact);
    }

    public function listTrashed()
    {
        return $this->contactRepository->onlyTrashedPaginate();
    }

    public function restore(int $id): void
    {
        $contact = $this->contactRepository->findWithTrashed($id);
        $this->contactRepository->restore($contact);
    }
}
