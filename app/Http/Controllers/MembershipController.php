<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
public function remove(Membership $membership)
{
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

    $memberBalance = $balances[$membership->user_id]['balance'];

    if ($memberBalance < 0) {
        $membership->user->reputation -= 1;
        $membership->user->save();

        $ownerMembership = $colocation->memberships->where('role', 'owner')->first();
    } else {
        $membership->user->reputation += 1;
        $membership->user->save();
    }

    $membership->left_at = now();
    $membership->save();

    return back()->with('success', 'Member removed successfully.');
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
