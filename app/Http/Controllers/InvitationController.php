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

    }

    public function accept($token) {

        $invitaion = Invitation::where('token',$token)
            ->where('status','pending')->firstOrFail();

        $user = auth()->user();
        
        if ($user->email !== $invitaion->email) {
            abort(403);
        }

        if ($user->activeMembership) {
            to_route('dashboard');
        }

        Membership::create([
            'user_id' => $user->id,
            'colocation_id' => $invitaion->colocation_id,
            'role' => 'member',
            'joindes_at' => now(),
        ]);

        $invitaion->update([
            'status' => 'accepted'
        ]);

        return to_route('colocations.show',$invitaion->colocation_id);
    } 



}
