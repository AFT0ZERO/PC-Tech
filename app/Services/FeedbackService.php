<?php

namespace App\Services;

use App\Models\Feedback;
use App\Repositories\FeedbackRepository;

class FeedbackService
{
    public function __construct(private FeedbackRepository $feedbackRepository)
    {
    }

    public function getFormData(): array
    {
        return [
            'products' => $this->feedbackRepository->allProducts(),
            'users' => $this->feedbackRepository->allUsers(),
        ];
    }

    public function create(array $data): Feedback
    {
        return $this->feedbackRepository->create($data);
    }

    public function update(Feedback $feedback, array $data): void
    {
        $this->feedbackRepository->update($feedback, $data);
    }

    public function delete(Feedback $feedback): void
    {
        $this->feedbackRepository->delete($feedback);
    }
}
