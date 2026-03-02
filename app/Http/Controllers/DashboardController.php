<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Fetch active membership data
        $membership = $user->activeMembership;
        $colocation = $membership ? $membership->colocation : null;

        // Reputation score
        $reputation = $user->reputation ?? 0;

        // Expense score (total amount spent by user)
        $expenseScore = Expense::where('user_id', $user->id)->sum('amount');

        // Recent expenses (all colocations)
        $recentExpenses = Expense::where('user_id', $user->id)
            ->with('colocation', 'category')
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', [
            'user' => $user,
            'colocation' => $colocation,
            'reputation' => $reputation,
            'expenseScore' => $expenseScore,
            'recentExpenses' => $recentExpenses,
        ]);
    }
}
