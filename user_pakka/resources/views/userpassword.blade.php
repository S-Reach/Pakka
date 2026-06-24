@extends('layout.master')

@section('content')
<title>Edit Password</title>
<link rel="stylesheet" href="{{ asset('css/userpassword.css') }}">
<script src="{{ asset('js/userpassword.js') }}" defer></script>

<div class="container">

    <div class="back-btn" onclick="history.back()">
        <i class="fa-solid fa-arrow-left"></i>
        Back
    </div>

    <h2>Password & Security</h2>
    <p>Manage your password and account security</p>

    <div class="card">

        <form method="POST" action="{{ route('userpassword.update') }}">
            @csrf

            {{-- Current Password --}}
            @if(Auth::user()->password)
            <div class="mb-3">
                <label>Current Password</label>
                <div class="password-wrapper">
                    <input type="password" name="current_password" id="current_password" class="form-control" placeholder="Enter current password">

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
                <label>New Password</label>
                <div class="password-wrapper">
                    <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Enter new password">

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
                <label>Confirm New Password</label>
                <div class="password-wrapper">
                    <input type="password" name="new_password_confirmation" id="confirm_password" class="form-control" placeholder="Confirm new password">

                    <span>
                        <i class="fa fa-eye" onclick="togglePassword('confirm_password', this)"></i>
                    </span>
                </div>
            </div>

            <button class="btn-dark">
                Update Password
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
        <h4>Security Tips</h4>
        <ul>
            <li>Use at least 8 characters</li>
            <li>Mix uppercase, lowercase, numbers, and special character</li>
            <li>Don’t reuse passwords</li>
            <li>Change regularly</li>
        </ul>
    </div>

</div>

@endsection