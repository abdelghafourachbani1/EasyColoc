<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Membership;
use Illuminate\Http\Request;

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

    public function show(Request $request)
    {
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

}
