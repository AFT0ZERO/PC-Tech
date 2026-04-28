<?php

namespace App\Policies;

use App\Models\Build;
use App\Models\User;

class BuildPolicy
{
    /**
     * Only the build owner may delete their build.
     */
    public function delete(User $user, Build $build): bool
    {
        return $user->id === $build->user_id;
    }
}
