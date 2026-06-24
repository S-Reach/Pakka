<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class CheckUserSuspended
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->isSuspended()) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login')->with('error', 'Your account has been suspended.');
        }

        return $next($request);
    }
}
