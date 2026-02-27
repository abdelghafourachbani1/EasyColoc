<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function store(Request $request, $colocationId) {
        $user = auth()->user();

        $membership = $user->memberships()->where('colocation_id', $colocationId)
                            >whereNull('left_at')->first();

        if (!$membership) {
            return back()->with('info', 'You are not an active member of this colocation.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
        ]);

        Expense::create([
            'colocation_id' => $colocationId,
            'user_id' => $user->id,
            'title' => $request->title,
            'amount' => $request->amount,
            'date' => $request->date,
            'category_id' => $request->category_id,
        ]);

        return back()->with('success', 'Expense added successfully.');
    }



}
