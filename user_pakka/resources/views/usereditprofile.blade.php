@extends('layout.master')

@section('content')

<title>User Profile</title>
<link rel="stylesheet" href="{{ asset('css/usereditprofile.css') }}">
<script src="{{ asset('js/usereditprofile.js') }}" defer></script>


<div class="wrapper">

    <h2>Profile Information</h2>
    <p class="subtitle">Manage your personal details and public profile</p>

    <div class="profile-container">

        <!-- LEFT -->
        <div class="left-section">

            <!-- Profile Card -->
            <div class="profile-card">
                <div class="avatar">
                    @if($user->avatar)
                        <img id="avatarPreview" src="{{ asset('storage/'.$user->avatar) }}">
                    @else
                        <div class="avatar-placeholder">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <div class="username">{{ $user->username }}</div>
                <div class="handle">{{ '@'.$user->username }}</div>

                <!-- Button triggers hidden input -->
                <label for="avatarInput" class="change-btn">
                    <!-- Upload Icon -->
                    <svg width="18" height="18" fill="none" stroke="#111" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 16V4"></path>
                        <path d="M8 8l4-4 4 4"></path>
                        <path d="M4 20h16"></path>
                    </svg>
                    Change Avatar
                </label>
            </div>

            <!-- Stats -->
            <div class="stats-card">
                <h4>Account Stats</h4>

                <div class="stat-row">
                    <span class="stat-left">
                        <svg width="18" height="18" fill="none" stroke="#6b7280" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M17 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M7 21v-2a4 4 0 0 1 3-3.87"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Followers
                    </span>
                    <strong id="followersCount">{{ $followers  }}</strong>
                </div>

                <div class="stat-row">
                    <span class="stat-left">
                        <svg width="18" height="18" fill="none" stroke="#6b7280" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M17 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M7 21v-2a4 4 0 0 1 3-3.87"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Following
                    </span>
                    <strong>{{ $following }}</strong>
                </div>

                <div class="stat-row">
                    <span class="stat-left">
                        <svg width="18" height="18" fill="none" stroke="#6b7280" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v15H6.5A2.5 2.5 0 0 0 4 19.5V2z"></path>
                        </svg>
                        Library
                    </span>
                    <strong>{{ $user->library->count() }}</strong>
                </div>
            </div>

        </div>

        <!-- RIGHT -->
        <div class="right-card">
            <h3>Edit Profile</h3>

            <form method="POST" action="{{ route('userprofile.update') }}" enctype="multipart/form-data">
                @csrf

                <!-- Hidden Avatar Input -->
                <input type="file" name="avatar" id="avatarInput" hidden>

                <div class="name-row">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="first_name" value="{{ $user->first_name }}">
                    </div>

                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" value="{{ $user->last_name }}">
                    </div>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="text" value="{{ $user->email }}" disabled>
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" value="{{ $user->username }}" disabled>
                </div>

                <div class="form-group">
                    <label>Bio</label>
                    <textarea name="bio">{{ $user->bio }}</textarea>
                </div>

                <button class="btn-save">Save Profile Changes</button>
            </form>
        </div>

    </div>
</div>
@endsection