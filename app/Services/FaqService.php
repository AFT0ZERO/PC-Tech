<?php

namespace App\Services;

use App\Models\Faqs;
use App\Repositories\FaqRepository;

class FaqService
{
    public function __construct(private FaqRepository $faqRepository)
    {
    }

    public function list(?string $search = null)
    {
        return $this->faqRepository->paginateWithSearch($search);
    }

    public function create(array $data): Faqs
    {
        return $this->faqRepository->create($data);
    }

    public function update(Faqs $faq, array $data): void
    {
        $this->faqRepository->update($faq, $data);
    }

    public function delete(Faqs $faq): void
    {
        $this->faqRepository->delete($faq);
    }

    public function listTrashed()
    {
        return $this->faqRepository->onlyTrashedPaginate();
    }

    public function restore(int $id): void
    {
        $faq = $this->faqRepository->findWithTrashed($id);
        $this->faqRepository->restore($faq);
    }
}
