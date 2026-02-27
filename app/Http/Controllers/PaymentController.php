<?php

namespace App\Http\Controllers;

use App\Models\Payments;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request) {
        $request->validate([
            'colocation_id' => 'required|exists:colocations,id',
            'from_user_id'  => 'required|exists:users,id',
            'to_user_id'    => 'required|exists:users,id',
            'amount'        => 'required|numeric|min:0.01',
        ]);

        Payments::create($request->all());

        return back()->with('success', 'Payment recorded ');
    }

}
