<?php

namespace App\Services;

use App\Models\Colocation;

class BalanceService
{
    public function getUserBalance(Colocation $colocation, int $userId): float
    {
        $balance = 0;

        $expenses = $colocation->expenses()->with('participants')->get();

        foreach ($expenses as $expense) {

            $totalParticipants = $expense->participants->count();

            if ($totalParticipants === 0) {
                continue;
            }

            $share = $expense->amount / $totalParticipants;

            // ✅ If user paid → others owe him
            if ($expense->user_id === $userId) {
                $balance += $expense->amount - $share;
            }

            // ✅ If user is participant → he owes his share
            if ($expense->participants->contains('id', $userId)) {
                $balance -= $share;
            }
        }

        return round($balance, 2);
    }
}
