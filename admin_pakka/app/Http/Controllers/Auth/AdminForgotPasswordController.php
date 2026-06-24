<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminForgotPasswordController extends Controller
{
    // Show forgot page
    public function showForgotForm()
    {
        return view('auth.adminforgot');
    }

    // Send email
    public function sendResetLink(Request $request)
    {
        $request->validate([

            'email'=>'required|email'

        ]);

        $status = Password::broker('admins')

            ->sendResetLink(
                $request->only('email')
            );

        return $status === Password::RESET_LINK_SENT

            ? back()->with(
                'success',
                __($status)
            )

            : back()->withErrors([
                'email'=>__($status)
            ]);
    }

    // Show reset page
    public function showResetForm(
        Request $request,
        $token
    )
    {
        return view(
            'auth.adminreset',
            [
                'token'=>$token,
                'email'=>$request->email
            ]
        );
    }

    // Reset password
    public function reset(Request $request)
    {
        $request->validate([
            'token'=>'required',
            'email'=>'required|email',
            'password'=>'required|min:8|confirmed',
        ]);

        $status = Password::broker('admins')
            ->reset(
                $request->only(
                    'email',
                    'password',
                    'password_confirmation',
                    'token'
                ),

                function ($admin, $password) {
                    $admin->password = Hash::make($password);
                    $admin->setRememberToken(
                        Str::random(60)
                    );
                    $admin->save();
                }
            );

        return $status === Password::PASSWORD_RESET
            ? redirect()
                ->route('login')
                ->with(
                    'success',
                    'Password reset successfully.'
                )
            : back()->withErrors([
                'email'=>__($status)
            ]);
    }
}