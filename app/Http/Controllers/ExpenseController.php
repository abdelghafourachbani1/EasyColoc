<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function store(Request $request , Colocation $colocation) {
        $request->validate([
            'title' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
        ]);

        Expense::created([
            'colocation_id' => $colocation->id,
            'user_id' => auth()->id(),
            'title' => $request->title,
            'amount' => $request->amount,
            'date' => $request->date,
            'category_id' => $request->category_id,
        ]);

        return back()->with('success','Expense Added');

    }


}
