<?php

namespace App\Services;

use App\Models\User;

class ReputationService
{
    public function reward(User $user, int $points = 1): void
    {
        $user->increment('reputation', $points);
    }

    public function penalize(User $user, int $points = 1): void
    {
        $user->decrement('reputation', $points);
    }
}
