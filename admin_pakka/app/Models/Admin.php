<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Auth\Notifications\ResetPassword;

class Admin extends Authenticatable
{
    use Notifiable, CanResetPasswordTrait;

    protected $fillable = [
        'firstname','lastname', 'email', 'password','status',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sendPasswordResetNotification($token)
    {
        ResetPassword::createUrlUsing(function ($user, string $token) {

            return route('admin.password.reset', [

                'token' => $token,

                'email' => $user->email,

            ]);

        });

        $this->notify(new ResetPassword($token));
    }
}
