<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckBanned
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user && $user->is_banned) {
            auth()->logout();
            return redirect()->route('login')->with('info', 'Your account has been banned.');
        }

        return $next($request);
    }
}
