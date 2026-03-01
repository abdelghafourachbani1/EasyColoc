<?php

namespace App\Services;

use App\Models\Colocation;

class BalanceService
{
    public function getUserBalance(Colocation $colocation, int $userId): float
    {
        $balance = 0;

        $expenses = $colocation->expenses()
            ->with('participants')
            ->get();

        foreach ($expenses as $expense) {

            $totalParticipants = $expense->participants->count();

            if ($totalParticipants === 0) {
                continue;
            }

            $share = $expense->amount / $totalParticipants;

            $isParticipant = $expense->participants->contains('id', $userId);
            $isPayer = $expense->user_id === $userId;

            if ($isPayer) {
                $balance += $expense->amount;
            }

            if ($isParticipant) {
                $balance -= $share;
            }
        }

        foreach ($colocation->payments as $payment) {

            if ($payment->from_user_id === $userId) {
                $balance += $payment->amount; 
            }

            if ($payment->to_user_id === $userId) {
                $balance -= $payment->amount; 
            }
        }

        return round($balance, 2);
    }
}
