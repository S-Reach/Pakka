<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Pakka Register</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
<script src="{{ asset('js/auth/register.js') }}" defer></script>

</head>

<body>

<div class="container">

<div class="logo">
    <div class="logo-circle">P</div>
    <h1>Pakka</h1>
    <div class="subtitle">{{ __('ui.register_hero') }}</div>
</div>

<h2>{{ __('ui.create_account') }}</h2>
<div class="desc">{{ __('ui.register_desc') }}</div>

@if(session('success'))
<div class="note">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('register.store') }}">
@csrf

{{-- NAME ROW --}}
<div style="display: flex; gap: 100px; width: 90%;">

    <div style="flex:1;">
        <label>{{ __('ui.first_name') }}</label>
        <div class="input-group">
            <i class="fa fa-user left"></i>
            <input type="text" name="first_name" required
                value="{{ old('first_name') }}"
                class="@error('first_name') error @enderror"
                placeholder="{{ __('ui.first_name') }}">
        </div>
        @error('first_name') <div class="error-text">{{ $message }}</div> @enderror
    </div>

    <div style="flex:1;">
        <label>{{ __('ui.last_name') }}</label>
        <div class="input-group">
            <i class="fa fa-user left"></i>
            <input type="text" name="last_name" required
                value="{{ old('last_name') }}"
                class="@error('last_name') error @enderror"
                placeholder="{{ __('ui.last_name') }}">
        </div>
        @error('last_name') <div class="error-text">{{ $message }}</div> @enderror
    </div>

</div>

{{-- USERNAME --}}
<label>{{ __('ui.username') }}</label>
<div class="input-group">
    <i class="fa fa-user left"></i>
    <input type="text" name="username" required value="{{ old('username') }}"
        class="@error('username') error @enderror"
        placeholder="{{ __('ui.username') }}">
</div>
@error('username') <div class="error-text">{{ $message }}</div> @enderror

{{-- EMAIL --}}
<label>{{ __('ui.email') }}</label>
<div class="input-group">
    <i class="fa fa-envelope left"></i>
    <input type="email" name="email" required value="{{ old('email') }}"
        class="@error('email') error @enderror"
        placeholder="{{ __('ui.enter_email') }}">
</div>
@error('email') <div class="error-text">{{ $message }}</div> @enderror

{{-- PASSWORD --}}
<label>{{ __('ui.password') }}</label>
<div class="input-group">
    <i class="fa fa-lock left"></i>
    <input type="password" id="password" name="password" required
        class="@error('password') error @enderror"
        placeholder="{{ __('ui.enter_new_password') }}">
    <i class="fa fa-eye right" onclick="togglePassword('password', this)"></i>
</div>
<p class="password-note">
    <i class="fa-solid fa-circle-info"></i>
    {{ __('ui.password_security_tips') }}
</p>
@error('password') <div class="error-text">{{ $message }}</div> @enderror

{{-- CONFIRM PASSWORD --}}
<label>{{ __('ui.confirm_password') }}</label>
<div class="input-group">
    <i class="fa fa-lock left"></i>
    <input type="password" id="confirmPassword" name="password_confirmation" required
        placeholder="{{ __('ui.confirm_new_password') }}">
    <i class="fa fa-eye right" onclick="togglePassword('confirmPassword', this)"></i>
</div>

{{-- AGREEMENT --}}
<div class="agreement">
    <label class="checkbox-container">
        <input
            type="checkbox"
            name="agreement"
            required>
        <span>
            {{ __('ui.agree') }}
            <a href="{{ route('terms') }}" target="_blank">
                {{ __('ui.footer_terms') }}
            </a>
            and
            <a href="{{ route('privacy') }}" target="_blank">
                {{ __('ui.footer_privacy') }}
            </a>.
        </span>
    </label>
    @error('agreement')
        <div class="error-text">{{ $message }}</div>
    @enderror
</div>

<button type="submit">{{ __('ui.sign_up') }}</button>

</form>

<div class="link">
    {{ __('ui.account_existed') }} <a href="{{ route('login') }}"> {{ __('ui.sign_in') }} </a>
</div>

<div class="note">
<strong>{{ __('ui.register_bold') }}</strong><br>
{{ __('ui.register_text') }}
</div>

<div class="back">
<a href="/">← {{ __('ui.back_to_home') }}</a>
</div>

</div>

</body>
</html>