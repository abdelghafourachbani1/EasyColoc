<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Membership;
use Illuminate\Http\Request;
use App\Services\BalanceService;
use App\Services\ReputationService;
use App\Models\Payments;


class ColocationController extends Controller {

    public function store(Request $request) {
        $user = auth()->user();

        if ($user->activeMembership) {
            return back()->with('error','you already have an active colocation');
        }

        $colocation = Colocation::create([
            'title' => $request->title,
            'owner_id' => $user->id,
            'status' => 'active',
        ]);

        Membership::create([
            'user_id' => $user->id,
            'colocation_id' => $colocation->id,
            'role' => 'owner' ,
            'joined_at' => now()
        ]);

        return to_route('dashboard')->with('success','colcation created succesfully');
    }

    public function show(Request $request) {
        $user = auth()->user();

        $membership = $user->activeMembership;

        if (!$membership) {
            return to_route('dashboard')->with('info', 'you have no active colocation');
        }

        $month = $request->month;

        $colocation = $membership->colocation()
            ->with([
                'memberships.user',
                'categories',
                'expenses' => function ($query) use ($month) {
                    if ($month) {
                        $query->whereMonth('date', substr($month, 5, 2))
                            ->whereYear('date', substr($month, 0, 4));
                    }
                },
                'expenses.payeur',
                'expenses.category'
            ])
            ->first();

        $balances = [];
        $members = $colocation->memberships->whereNull('left_at')->pluck('user');

        foreach ($members as $member) {
            $balances[$member->id] = [
                'user' => $member,
                'paid' => 0,
                'share' => 0,
                'balance' => 0
            ];
        }

        foreach ($colocation->expenses as $expense) {
            $numMembers = count($members);
            $share = $expense->amount / $numMembers;

            $balances[$expense->user_id]['paid'] += $expense->amount;

            foreach ($members as $member) {
                $balances[$member->id]['share'] += $share;
            }
        }

        foreach ($balances as &$b) {
            $b['balance'] = $b['paid'] - $b['share'];
        }

        return view('colocation.show', compact('colocation', 'balances', 'month'));
    }

    public function cancel(Colocation $colocation, BalanceService $balanceService, ReputationService $reputationService) {
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
                    'from_user_id'  => $colocation->owner_id,
                    'to_user_id'    => $membership->user_id,
                    'amount'        => abs($balance),
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
