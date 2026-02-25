<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function remove(Membership $membership) {
        $colocation = $membership->colocation;
        if ($colocation->owner_id !== auth()->id()) {
            abort(403);
        }

        $membership->update([
            'left_at' => now()
        ]);

        return back();
    }

    public function leave() {

        auth()->user()->activeMembership->update([
            'left_at' => now()
        ]);

        return to_route('dashboard');
    }
}
