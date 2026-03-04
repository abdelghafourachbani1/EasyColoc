<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Membership;
use Illuminate\Http\Request;
use App\Services\BalanceService;
use App\Services\ReputationService;
use App\Models\Payments;
use App\Services\BalanceService as ServicesBalanceService;

class ColocationController extends Controller
{

    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->activeMembership) {
            return back()->with('error', 'you already have an active colocation');
        }

        $colocation = Colocation::create([
            'title' => $request->title,
            'owner_id' => $user->id,
            'status' => 'active',
        ]); 

        Membership::create([
            'user_id' => $user->id,
            'colocation_id' => $colocation->id,
            'role' => 'owner',
            'joined_at' => now()
        ]);

        return to_route('dashboard')->with('success', 'colcation created succesfully');
    }

    public function show(Colocation $colocation, Request $request, BalanceService $balanceService)
    {

        $colocation->load([
            'memberships.user',
            'expenses.payeur',
            'expenses.category',
            'categories'
        ]);

        $month = $request->get('month');

        $expensesQuery = $colocation->expenses();

        if ($month) {
            $expensesQuery->whereYear('date', substr($month, 0, 4))
                ->whereMonth('date', substr($month, 5, 2));
        }

        $expenses = $expensesQuery->with('payeur', 'category')->get();

        $activeMemberships = $colocation->memberships()->whereNull('left_at')->with('user')->get();

        $balances = [];
        foreach ($activeMemberships as $membership) {
            $balances[] = [
                'user' => $membership->user,
                'paid' => $expenses->where('user_id', $membership->user_id)->sum('amount'),
                'share' => $expenses->sum('amount') / max(1, $activeMemberships->count()), 
                'balance' => $balanceService->getUserBalance($colocation, $membership->user_id) 
            ];
        }

        $settlements = $balanceService->getSettlements($colocation);

        $totalVolume = $expenses->sum('amount');

        return view('colocations.show', [
            'colocation' => $colocation,
            'balances' => $balances,
            'month' => $month,
            'expenses' => $expenses,
            'settlements' => $settlements,
            'totalVolume' => $totalVolume,
        ]);
    }


    public function cancel(Colocation $colocation, BalanceService $balanceService, ReputationService $reputationService)
    {
        if (auth()->id() !== $colocation->owner_id) {
            abort(403);
        }

        $members = $colocation->memberships->whereNull('left_at');

        foreach ($members as $membership) {
            if ($membership->user_id === $colocation->owner_id) {
                continue;
            }

            $balance = $balanceService->getUserBalance($colocation, $membership->user_id);

            if ($balance < 0) {
                Payments::create([
                    'colocation_id' => $colocation->id,
                    'from_user_id' => $colocation->owner_id,
                    'to_user_id' => $membership->user_id,
                    'amount' => abs($balance),
                ]);

                $reputationService->penalize($membership->user);
            } else {
                $reputationService->reward($membership->user);
            }

            $membership->update([
                'left_at' => now()
            ]);
        }

        $colocation->update([
            'status' => 'cancelled'
        ]);

        return to_route('dashboard')->with('success', 'Colocation cancelled successfully.');
    }

}
