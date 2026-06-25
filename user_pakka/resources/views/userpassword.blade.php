@extends('layout.master')

@section('content')
<title>Edit Password</title>
<link rel="stylesheet" href="{{ asset('css/userpassword.css') }}">
<script src="{{ asset('js/userpassword.js') }}" defer></script>

<div class="container">

    <div class="back-btn" onclick="history.back()">
        <i class="fa-solid fa-arrow-left"></i>
        {{ __('ui.back') }}
    </div>

    <h2>{{ __('ui.password_security') }}</h2>
    <p>{{ __('ui.manage_password_security') }}</p>

    <div class="card">

        <form method="POST" action="{{ route('userpassword.update') }}">
            @csrf

            {{-- Current Password --}}
            @if(Auth::user()->password)
            <div class="mb-3">
                <label>{{ __('ui.current_password') }}</label>
                <div class="password-wrapper">
                    <input type="password" name="current_password" id="current_password" class="form-control" placeholder="{{ __('ui.enter_current_password') }}">

                    <span>
                        <i class="fa fa-eye" onclick="togglePassword('current_password', this)"></i>
                    </span>
                </div>
                @error('current_password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            @endif

            {{-- New Password --}}
            <div class="mb-3">
                <label>{{ __('ui.new_password') }}</label>
                <div class="password-wrapper">
                    <input type="password" name="new_password" id="new_password" class="form-control" placeholder="{{ __('ui.enter_new_password') }}">

                    <span>
                        <i class="fa fa-eye" onclick="togglePassword('new_password', this)"></i>
                    </span>
                </div>
                @error('new_password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="mb-3">
                <label>{{ __('ui.confirm_new_password') }}</label>
                <div class="password-wrapper">
                    <input type="password" name="new_password_confirmation" id="confirm_password" class="form-control" placeholder="{{ __('ui.confirm_new_password') }}">

                    <span>
                        <i class="fa fa-eye" onclick="togglePassword('confirm_password', this)"></i>
                    </span>
                </div>
            </div>

            <button class="btn-dark">
                {{ __('ui.update_password') }}
            </button>
        </form>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert-success mt-4">
                {{ session('success') }}
            </div>
        @endif

    </div>

    {{-- Security Tips --}}
    <div class="card mt-4">
        <h4>{{ __('ui.security_tips') }}</h4>
        <ul>
            <li>{{ __('ui.use_at_least_8_characters') }}</li>
            <li>{{ __('ui.mix_uppercase_lowercase_numbers') }}</li>
            <li>{{ __('ui.dont_reuse_passwords') }}</li>
            <li>{{ __('ui.change_password_regularly') }}</li>
        </ul>
    </div>

</div>

@endsection