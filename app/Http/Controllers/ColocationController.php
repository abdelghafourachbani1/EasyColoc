<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Membership;
use Illuminate\Http\Request;

class ColocationController extends Controller
{
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
}
