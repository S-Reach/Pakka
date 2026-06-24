<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    // SHOW LOGIN PAGE
    public function showLogin()
    {
        return view('auth.adminlogin');
    }

    // LOGIN
    public function login(Request $request)
    {
        $credentials = $request->validate([

            'email' => 'required|email',

            'password' => 'required',

        ]);

        // ADMIN LOGIN
        if (
            Auth::guard('admin')->attempt(
                [
                    'email' => $request->email,
                    'password' => $request->password,
                    'status' => 1
                ],
                $request->remember
            )
        ) {

            $request->session()->regenerate();

            return redirect()->route('admindashboard');
        }

        return back()->with(
            'error',
            'Invalid email/password or inactive admin account.'
        );
    }

    // LOGOUT
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}