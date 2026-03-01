<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;
use App\Services\BalanceService;
use App\Models\Payments;

class MembershipController extends Controller {

    public function remove(Membership $membership, BalanceService $balanceService) {
        $colocation = $membership->colocation;

        if (auth()->id() !== $colocation->owner_id) {
            abort(403);
        }

        if ($membership->user_id === $colocation->owner_id) {
            return back()->with('info', 'Owner cannot be removed');
        }

        if ($membership->left_at) {
            return back()->with('info', 'Member already left.');
        }

        $balance = $balanceService->getUserBalance($colocation, $membership->user_id);

        if ($balance < 0) {
            Payments::create([
                'colocation_id' => $colocation->id,
                'from_user_id'  => $colocation->owner_id,
                'to_user_id'    => $membership->user_id,
                'amount'        => abs($balance),
            ]);

            $membership->user->decrement('reputation');
        }

        if ($balance > 0) {
            $membership->user->increment('reputation');
        }

        $membership->update([
            'left_at' => now()
        ]);

        return back()->with('success', 'Member removed successfully');
    }

    public function leave() {
        $user = auth()->user();
        $membership = $user->activeMembership;

        if (!$membership) {
            return back()->with('info', 'You have no active colocation.');
        }

        $colocation = $membership->colocation()->with('memberships.user', 'expenses.payeur', 'expenses.category')->first();

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

        foreach ($balances as $id => &$b) {
            $b['balance'] = $b['paid'] - $b['share'];
        }

        $userBalance = $balances[$user->id]['balance'];

        if ($userBalance < 0) {
            $user->reputation -= 1; 
            $ownerMembership = $colocation->owner;
        } else {
            $user->reputation += 1;
        }

        $user->save();

        $membership->left_at = now();
        $membership->save();

        return to_route('dashboard')->with('success', 'You have left the colocation.');
    }


}
