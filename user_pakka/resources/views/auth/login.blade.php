<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Pakka Login</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Battambang:wght@300;400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
<script src="{{ asset('js/auth/login.js') }}" defer></script>

</head>

<body>

<div class="container">

    <div class="logo">
        <div class="logo-circle">
            <img src="{{ asset('images/logo.png') }}" alt="Pakka Logo" class="pk-logo-img">
        </div>
        <h1>Pakka</h1>
        <div class="subtitle">{{ __('ui.register_hero') }}</div>
    </div>

    <h2>{{ __('ui.welcome_back') }}</h2>
    <div class="desc">{{ __('ui.sign_in_desc') }}</div>

    {{-- Error Message --}}
    @if($errors->any())
        <div style="background:#fee2e2; color:#b91c1c; padding:10px; 
                    border-radius:10px; margin-bottom:10px;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.store') }}">
        @csrf

        <label>{{ __('ui.email') }}</label>
        <div class="input-group">
            <i class="fa fa-envelope left"></i>
            <input type="email" name="email" placeholder="{{ __('ui.enter_email') }}" value="{{ old('email') }}">
        </div>

        <label>{{ __('ui.password') }}</label>
        <div class="input-group">
            <i class="fa fa-lock left"></i>
            <input type="password" name="password" id="password" placeholder="{{ __('ui.enter_current_password') }}">
            <i class="fa fa-eye right" onclick="togglePassword('password', this)"></i>
        </div>

        <div style="text-align:right; margin-bottom:10px;">
            <a href="{{ route('forgotpassword.request') }}" style="font-size:13px; color:#7c3aed;">
                {{ __('ui.forgot_password') }}
            </a>
        </div>

        <button type="submit">{{ __('ui.sign_in') }}</button>
    </form>

    <div class="link">
        {{ __('ui.dont_have_account') }} <a href="{{ route('register') }}">{{ __('ui.sign_up') }}</a>
    </div>

    <div class="back">
        <a href="/">← {{ __('ui.back_to_home') }}</a>
    </div>

</div>

</body>
</html>