<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountSettingsController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            abort(403, 'Unauthorized');
        }

        return view('account-settings', compact('admin'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'firstname' => 'required|max:255',
            'lastname'  => 'required|max:255',
            'email'     => 'required|email|max:255',
        ]);

        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            abort(403, 'Unauthorized');
        }

        $admin->update([
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'email'     => $request->email,
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
        ]);

        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            abort(403, 'Unauthorized');
        }

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect.'
            ]);
        }

        $admin->password = Hash::make($request->new_password);
        $admin->save();

        return back()->with('success', 'Password updated successfully.');
    }
}