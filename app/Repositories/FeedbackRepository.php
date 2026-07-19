<?php

namespace App\Repositories;

use App\Models\Feedback;
use App\Models\Product;
use App\Models\User;

class FeedbackRepository
{
    public function create(array $data): Feedback
    {
        return Feedback::create($data);
    }

    public function update(Feedback $feedback, array $data): bool
    {
        return $feedback->update($data);
    }

    public function delete(Feedback $feedback): ?bool
    {
        return $feedback->delete();
    }

    public function allProducts()
    {
        return Product::all();
    }

    public function allUsers()
    {
        return User::all();
    }
}
