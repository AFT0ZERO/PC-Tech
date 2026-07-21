<?php

namespace App\Policies;

use App\Models\Build;
use App\Models\User;

class BuildPolicy
{
    /**
     * Only the build owner may view their build's details.
     */
    public function view(User $user, Build $build): bool
    {
        return $user->id === $build->user_id;
    }

    /**
     * Only the build owner may modify (add/remove/re-quantify items) their build.
     */
    public function update(User $user, Build $build): bool
    {
        return $user->id === $build->user_id;
    }

    /**
     * Only the build owner may delete their build.
     */
    public function delete(User $user, Build $build): bool
    {
        return $user->id === $build->user_id;
    }
}
