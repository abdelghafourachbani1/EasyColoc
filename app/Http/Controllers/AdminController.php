<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Colocation;
use App\Models\Expense;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $this->authorize('admin'); 

        $stats = [
            'total_users'      => User::count(),
            'total_colocations' => Colocation::count(),
            'total_expenses'   => Expense::count(),
            'banned_users'     => User::where('banned', true)->count(),
        ];

        $users = User::orderBy('name')->get();

        return view('admin.dashboard', compact('stats', 'users'));
    }

    public function toggleBan(User $user)
    {
        $this->authorize('admin');

        $user->banned = !$user->banned;
        $user->save();

        return back()->with('success', 'User status updated.');
    }
}
