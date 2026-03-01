<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Colocation;
use App\Models\Payments;
use Illuminate\Http\Request;
use App\Services\BalanceService;

class PaymentController extends Controller
{
    public function store(Request $request, BalanceService $balanceService)
    {
        $data = $request->validate([
            'colocation_id' => 'required|exists:colocations,id',
            'from_user_id'  => 'required|exists:users,id',
            'to_user_id'    => 'required|exists:users,id',
            'amount'        => 'required|numeric|min:0.01',
        ]);

        $colocation = Colocation::findOrFail($data['colocation_id']);

        if (auth()->id() !== (int) $data['from_user_id']) {
            abort(403);
        }

        $memberIds = $colocation->memberships()
            ->whereNull('left_at')
            ->pluck('user_id');

        if (
            !$memberIds->contains($data['from_user_id']) ||
            !$memberIds->contains($data['to_user_id'])
        ) {
            abort(403);
        }

        $balance = $balanceService->getUserBalance(
            $colocation,
            $data['from_user_id']
        );

        if ($balance >= 0) {
            return back()->with('error', 'You have no debt.');
        }

        $realDebt = abs($balance);

        if ($data['amount'] > $realDebt) {
            return back()->with('error', 'Amount exceeds your debt.');
        }

        Payments::create($data);

        return back()->with('success', 'Payment recorded ');
    }
}
