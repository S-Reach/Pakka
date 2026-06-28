@extends('layout.master')

@section('content')
<title>Show Proile Detail</title>
<link rel="stylesheet" href="{{ asset('css/writeprofile.css') }}">
<script src="{{ asset('js/writeprofile.js') }}" defer></script>

<a href="{{ url()->previous() }}" class="back-btn">← Back</a>

<div class="profile-page-wrapper">
    
    <!-- LEFT -->
    <div class="profile-left">
        <div class="profile-card">

            <div class="avatar">
                @if(!empty($user->avatar))
                    <img src="{{ asset('storage/'.$user->avatar) }}"
                        alt="Avatar"
                        class="avatar-img">
                @else
                    <span class="avatar-text">
                        {{ strtoupper(substr($user->name,0,1)) }}
                    </span>
                @endif
            </div>

            <h2>{{ $user->name }}</h2>
            <p class="username">{{ '@'.$user->username }}</p>

            <!-- Show follow/unfollow button only if user is logged in and not viewing their own profile -->
            @if(auth()->check() && auth()->id() != $user->id)
                <button id="followBtn"
                    class="follow-btn"
                    data-following="{{ $isFollowing ? 'true' : 'false' }}">
                    {{ $isFollowing ? 'Unfollow' : 'Follow' }}
                </button>
            @endif

            <p>{{ $user->bio }}</p>

            <div style="margin-top:20px;">
                <div>{{ __('ui.stories') }}: <b>{{ $stories->count() }}</b></div>
                <div>{{ __('ui.followers') }}: <b>{{ $followersCount }}</b></div>
                <div>{{ __('ui.following') }}: <b>{{ $followingCount }}</b></div>
            </div>

        </div>
    </div>

    <!-- RIGHT -->
    <div class="profile-right">

        <!-- TABS -->
        <div class="tabs">
            <button class="tab-btn active" data-tab="stories">
                {{ __('ui.stories') }} ({{ $stories->count() }})
            </button>

            <button class="tab-btn" data-tab="followers">
                {{ __('ui.followers') }} ({{ $followersCount }})
            </button>

            <button class="tab-btn" data-tab="following">
                {{ __('ui.following') }} ({{ $followingCount }})
            </button>
        </div>

        <!-- STORIES -->
        <div class="tab-content active" id="stories">
            <div class="story-grid">

                @forelse($stories as $story)
                    <div class="story-card">
                        @if($story->cover_image)
                            <div class="story-cover">
                                <img src="{{ asset('storage/'.$story->cover_image) }}">
                            </div>
                        @else
                            <div class="story-cover">
                                <div style="font-size:50px;">📚</div>
                            </div>
                        @endif
                        <div class="story-body">
                            <h3>{{ $story->title }}</h3>
                            <p>{{ Str::limit($story->synopsis, 70) }}</p>

                            <small>
                                👁 {{ $story->views }}
                                ❤️ {{ $story->likes }}
                            </small>
                        </div>
                    </div>
                @empty
                    <p>{{ __('ui.no_stories_yet') }}</p>
                @endforelse

            </div>
        </div>

        <!-- FOLLOWERS -->
        <div class="tab-content" id="followers">
            <div class="user-list">

                @forelse($followersList as $follow)
                    <div class="user-item">
                        <div class="user-info">
                            <div class="user-avatar">
                                {{ strtoupper(substr($follow->follower->name,0,1)) }}
                            </div>

                            <div>
                                <strong>{{ $follow->follower->name }}</strong>
                                <p>{{ '@'.$follow->follower->username }}</p>
                            </div>
                        </div>

                        <a href="{{ route('writerprofile', $follow->follower->id) }}"
                           class="follow-btn"
                           style="width:auto;padding:8px 15px;">
                            View
                        </a>
                    </div>
                @empty
                    <p>{{ __('ui.no_followers_yet') }}</p>
                @endforelse

            </div>
        </div>

        <!-- FOLLOWING -->
        <div class="tab-content" id="following">
            <div class="user-list">

                @forelse($followingList as $follow)
                    <div class="user-item">
                        <div class="user-info">
                            <div class="user-avatar">
                                {{ strtoupper(substr($follow->following->name,0,1)) }}
                            </div>

                            <div>
                                <strong>{{ $follow->following->name }}</strong>
                                <p>{{ '@'.$follow->following->username }}</p>
                            </div>
                        </div>

                        <a href="{{ route('writerprofile', $follow->following->id) }}"
                           class="follow-btn"
                           style="width:auto;padding:8px 15px;">
                            View
                        </a>
                    </div>
                @empty
                    <p>{{ __('ui.no_following_yet') }}</p>
                @endforelse

            </div>
        </div>
    </div>
</div>


@endsection