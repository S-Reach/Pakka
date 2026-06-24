<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserPasswordController extends Controller
{
    // 👉 Show change password page
    public function index()
    {
        return view('userpassword'); // your blade name
    }

    // 👉 Handle password update
    public function update(Request $request)
    {
        $user = Auth::user();

        // 🟡 Case 1: User logged in via Facebook (no password yet)
        if (!$user->password) {
            $request->validate([
                'new_password' => 'required|min:6|confirmed',
            ]);

            $user->password = Hash::make($request->new_password);
            $user->save();

            return back()->with('success', 'Password set successfully!');
        }

        // 🔵 Case 2: Normal user (has password)
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect'
            ]);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password updated successfully!');
    }
}