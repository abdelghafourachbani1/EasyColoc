<?php

namespace App\Http\Controllers;

use App\Mail\ColocationInvitationMail;
use App\Models\Colocation;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Pest\Support\Str;

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
            'token' => Str::random(50),
        ]);

        Mail::to($request->email)->send(new ColocationInvitationMail($invitaion));

    }

    public function accept($token) 

}
