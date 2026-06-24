@extends('layout.master')

@section('content')

<title>Admin Setting</title>
<link rel="stylesheet" href="{{ asset('css/account-settings.css') }}">
<script src="{{ asset('js/account-settings.js') }}" defer></script>

<div class="container py-4">

    <!-- HEADER -->
    <div class="mb-4">
        <div class="page-title">Account Settings</div>
        <div class="page-sub">Manage your admin profile and security settings</div>
    </div>

    <!-- PROFILE -->
    <div class="card mb-3">
        <div class="profile">
            <div class="avatar">
                {{ strtoupper(substr($admin->firstname,0,1)) }}
            </div>

            <div>
                <div class="profile-name">
                    {{ $admin->firstname }} {{ $admin->lastname }}
                </div>
                <div class="profile-email">
                    {{ $admin->email }}
                </div>
            </div>
        </div>
    </div>

    <!-- TABS -->
    <div class="tabs">
        <button class="tab active" id="editTab">Edit Account</button>
        <button class="tab inactive" id="passTab">Change Password</button>
    </div>

    <!-- ACCOUNT FORM -->
    <div class="card" id="editSection">
        <div class="form-box">
            <div class="section-title">Account Information</div>

            <form action="{{ route('account.update') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label>First Name</label>
                    <input type="text" class="form-control"
                        value="{{ $admin->firstname }}">
                </div>

                <div class="mb-3">
                    <label>Last Name</label>
                    <input type="text" class="form-control"
                        value="{{ $admin->lastname }}">
                </div>

                <div class="mb-3">
                    <label>Email Address</label>
                    <input type="email" class="form-control"
                        value="{{ $admin->email }}">
                </div>

                <button class="btn-save">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- PASSWORD FORM -->
    <div class="card d-none" id="passSection">
        <div class="form-box">
            <div class="section-title">Change Password</div>

            <form action="{{ route('account.password') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label>Current Password</label>
                    <input type="password" name="current_password" class="form-control">
                </div>

                <div class="mb-3">
                    <label>New Password</label>
                    <input type="password" name="new_password" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Confirm Password</label>
                    <input type="password" name="new_password_confirmation" class="form-control">
                </div>

                <button class="btn-save">Update Password</button>
            </form>
        </div>
    </div>

</div>

@endsection