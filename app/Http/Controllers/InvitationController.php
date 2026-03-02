<?php

namespace App\Http\Controllers;

use App\Mail\ColocationInvitationMail;
use App\Models\Colocation;
use App\Models\Invitation;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InvitationController extends Controller
{

    public function store(Request $request , Colocation $colocation) {
        if ($colocation->owner_id !== auth()->id() ) {
            abort(403);
        }

        $request->validate([
            'email' => 'required|email'
        ]);

        $invitaion = Invitation::create([
            'email' => $request->email,
            'colocation_id' => $colocation->id,
            'token' => Str::random(40),
        ]);

        Mail::to($request->email)->send(new ColocationInvitationMail($invitaion));

        return to_route('colocations.show',$colocation)
                    ->with('success','Invitation sent successfully to'. $request->email);
    }

    public function accept($token) {
        $invitation = Invitation::where('token', $token)
            ->where('status', 'pending')
            ->firstOrFail();

        $user = auth()->user();

        if ($user->email !== $invitation->email) {
            abort(403, "This invitation is not for your account.");
        }

        $existing = Membership::where('user_id', $user->id)
            ->where('colocation_id', $invitation->colocation_id)
            ->first();

        if (!$existing) {
            Membership::create([
                'user_id' => $user->id,
                'colocation_id' => $invitation->colocation_id,
                'role' => 'member',
                'joined_at' => now(),
            ]);
        }

        $invitation->update([
            'status' => 'accepted'
        ]);

        return to_route('colocations.show', $invitation->colocation)
            ->with('success', 'You joined the colocation successfully!');
    }


}
