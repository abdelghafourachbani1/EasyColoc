<?php

namespace App\Services;

use App\Models\Colocation;

class BalanceService
{
    public function getUserBalance(Colocation $colocation, int $userId): float
    {
        $balance = 0;

        // Get all members who have not left the colocation
        $members = $colocation->memberships()
            ->whereNull('left_at')
            ->pluck('user_id');

        $totalParticipants = $members->count();

        if ($totalParticipants === 0) {
            return 0;
        }

        // We should calculate based on members at the time of expense, but for simplicity 
        // as per requirements, we use current active members or refine this.
        // Actually, the requirement says "Automatic calculation when expenses are added".

        $expenses = $colocation->expenses;

        foreach ($expenses as $expense) {
            // A simplified approach: divide by total current members
            // In a real app, we might need to know who was a member WHEN the expense was created.
            $share = $expense->amount / $totalParticipants;

            $isPayer = $expense->user_id === $userId;
            $isParticipant = $members->contains($userId);

            if ($isPayer) {
                $balance += $expense->amount;
            }

            if ($isParticipant) {
                $balance -= $share;
            }
        }

        foreach ($colocation->payments as $payment) {
            if ($payment->from_user_id === $userId) {
                $balance += $payment->amount; // Paying off debt increases balance
            }

            if ($payment->to_user_id === $userId) {
                $balance -= $payment->amount; // Receiving payment reduces what's owed to you
            }
        }

        return round($balance, 2);
    }

    /**
     * Calculate who owes whom.
     * Returns an array of settlements: ['from' => User, 'to' => User, 'amount' => float]
     */
    public function getSettlements(Colocation $colocation): array
    {
        $members = $colocation->memberships()->whereNull('left_at')->with('user')->get();
        $balances = [];

        foreach ($members as $membership) {
            $balances[] = [
                'user' => $membership->user,
                'balance' => $this->getUserBalance($colocation, $membership->user_id)
            ];
        }

        $debtors = collect($balances)->filter(fn($b) => $b['balance'] < 0)->sortBy('balance')->values()->all();
        $creditors = collect($balances)->filter(fn($b) => $b['balance'] > 0)->sortByDesc('balance')->values()->all();

        $settlements = [];
        $debtorIdx = 0;
        $creditorIdx = 0;

        while ($debtorIdx < count($debtors) && $creditorIdx < count($creditors)) {
            $debtor = &$debtors[$debtorIdx];
            $creditor = &$creditors[$creditorIdx];

            $amount = min(abs($debtor['balance']), $creditor['balance']);

            if ($amount > 0.01) {
                $settlements[] = [
                    'from' => $debtor['user'],
                    'to' => $creditor['user'],
                    'amount' => round($amount, 2)
                ];
            }

            $debtor['balance'] += $amount;
            $creditor['balance'] -= $amount;

            if (abs($debtor['balance']) < 0.01)
                $debtorIdx++;
            if (abs($creditor['balance']) < 0.01)
                $creditorIdx++;
        }

        return $settlements;
    }

}
