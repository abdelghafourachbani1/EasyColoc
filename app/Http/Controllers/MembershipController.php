<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;
use App\Services\BalanceService;
use App\Services\ReputationService;
use App\Models\Payments;

class MembershipController extends Controller
{

    public function remove(Membership $membership, BalanceService $balanceService, ReputationService $reputationService)
    {
        $colocation = $membership->colocation;

        if (auth()->id() !== $colocation->owner_id) {
            abort(403);
        }

        if ($membership->user_id === $colocation->owner_id) {
            return to_route('dashboard')->with('info', 'Owner cannot be removed. You can cancel the colocation instead.');
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

        return back()->with('success', 'Member removed successfully and debt transferred to owner.');
    }

    public function leave(BalanceService $balanceService, ReputationService $reputationService)
    {
        $user = auth()->user();
        $membership = $user->activeMembership;

        if (!$membership) {
            return back()->with('info', 'You have no active colocation.');
        }

        $colocation = $membership->colocation;

        if ($colocation->owner_id === $user->id) {
            return to_route('dashboard')->with('info', 'Owner cannot leave the colocation. Please cancel it instead if you wish to close it.');
        }


        $balance = $balanceService->getUserBalance($colocation, $user->id);

        if ($balance < 0) {
            $reputationService->penalize($user);
        } else {
            $reputationService->reward($user);
        }

        $membership->update([
            'left_at' => now()
        ]);

        return to_route('dashboard')
            ->with('success', 'You have left the colocation.');
    }

}
