<?php

namespace App\Services;

use App\Models\Colocation;

class BalanceService
{
    public function getUserBalance(Colocation $colocation, int $userId): float
    {
        $balance = 0;

        $members = $colocation->memberships()
            ->whereNull('left_at')
            ->pluck('user_id');

        $totalParticipants = $members->count();

        if ($totalParticipants === 0) {
            return 0;
        }

        $expenses = $colocation->expenses;

        foreach ($expenses as $expense) {

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
                $balance += $payment->amount; 
            }

            if ($payment->to_user_id === $userId) {
                $balance -= $payment->amount; 
            }
        }

        return round($balance, 2);
    }

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
