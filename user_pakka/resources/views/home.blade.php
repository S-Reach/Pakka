@extends('layout.master')

@section('content')

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Home Page</title>

<link rel="stylesheet" href="{{ asset('css/home.css') }}">

{{-- HERO --}}
<div class="hero">
    <h1>{{ __('ui.home_title') }}</h1>
    <p>{{ __('ui.home_desc') }}</p>

    <div class="hero-buttons">
        <a href="{{ route('browse') }}" class="btn-primary">{{ __('ui.start_reading') }}</a>
        <a href="{{ route('writer.dashboard') }}" class="btn-secondary">{{ __('ui.start_writing') }}</a>
    </div>
</div>

{{-- CONTINUE READING --}}
@if(Auth::check())
<div class="section">

    <div class="section-header">
        <h2>📚 {{ __('ui.continue_reading') }}</h2>
    </div>

    <div class="story-grid">

        @forelse($readingStories as $library)

            <a href="{{ route('reader.storydetail', $library->story->id) }}" class="story-card">

                <div class="story-image">

                    @if($library->story->cover_image)
                        <img src="{{ $library->story->cover_image_url }}">
                    @else
                        <div class="story-placeholder">📚</div>
                    @endif

                </div>

                <div class="story-content">

                    <div class="story-title">
                        {{ Str::limit($library->story->title, 40) }}
                    </div>

                    <div class="story-author">
                        {{ __('ui.by') }} {{ $library->story->user->username ?? 'Unknown' }}
                    </div>

                    <div class="story-meta">
                        <span>
                            {{ __('ui.last_read') }}:
                            {{ $library->lastChapter ? $library->lastChapter->title : 'Start Reading' }}
                        </span>
                    </div>

                </div>

            </a>

        @empty
            <p style="color:#6b7280;">{{ __('ui.no_reading_history') }}</p>
        @endforelse

    </div>
</div>
@endif

{{-- LATEST --}}
<div class="section">

    <div class="section-header">
        <h2>🕒 {{ __('ui.latest') }}</h2>
        <a href="{{ route('browse.latest') }}" class="view-all">{{ __('ui.view_all') }} →</a>
    </div>

    <div class="story-grid">

        @foreach($latestStories as $story)

            <a href="{{ route('reader.storydetail', $story->id) }}" class="story-card">

                <div class="story-image">

                    @if($story->cover_image)
                        <img src="{{ $story->cover_image_url }}">
                    @else
                        <div class="story-placeholder">📚</div>
                    @endif

                    @if($story->hasPaidChapters())
                        <div class="premium-badge">Premium</div>
                    @endif

                </div>

                <div class="story-content">

                    <div class="story-title">
                        {{ Str::limit($story->title,40) }}
                    </div>

                    <div class="story-author">
                        {{ __('ui.by') }} {{ $story->user->username ?? 'Unknown' }}
                    </div>

                    <div class="story-meta">
                        <span>👁 {{ $story->views ?? 0 }}</span>
                        <span>💬 {{ $story->comments_count ?? 0 }}</span>
                        <span>⭐ {{ $story->rating ?? 0 }}</span>
                    </div>

                </div>

            </a>

        @endforeach

    </div>
</div>

{{-- TRENDING --}}
<div class="section">

    <div class="section-header">
        <h2>📈 {{ __('ui.trending_stories') }}</h2>
        <a href="{{ route('browse.trending') }}" class="view-all">{{ __('ui.view_all') }} →</a>
    </div>

    <div class="story-grid">

        @foreach($trendingStories as $story)

            <a href="{{ route('reader.storydetail', $story->id) }}" class="story-card">

                <div class="story-image">

                    @if($story->cover_image)
                        <img src="{{ $story->cover_image_url }}">
                    @else
                        <div class="story-placeholder">📚</div>
                    @endif

                </div>

                <div class="story-content">

                    <div class="story-title">
                        {{ Str::limit($story->title,40) }}
                    </div>

                    <div class="story-author">
                        {{ __('ui.by') }} {{ $story->user->username ?? 'Unknown' }}
                    </div>

                    <div class="story-meta">
                        <span>👁 {{ $story->views ?? 0 }}</span>
                        <span>💬 {{ $story->comments_count ?? 0 }}</span>
                        <span>⭐ {{ $story->rating ?? 0 }}</span>
                    </div>

                </div>

            </a>

        @endforeach

    </div>
</div>

{{-- BEST COMPLETE --}}
<div class="section">

    <div class="section-header">
        <h2>✅ {{ __('ui.best_complete') }}</h2>
        <a href="{{ route('browse.complete') }}" class="view-all">{{ __('ui.view_all') }} →</a>
    </div>

    <div class="story-grid">

        @foreach($completedStories as $story)

            <a href="{{ route('reader.storydetail', $story->id) }}" class="story-card">

                <div class="story-image">

                    @if($story->cover_image)
                        <img src="{{ $story->cover_image_url }}">
                    @else
                        <div class="story-placeholder">📚</div>
                    @endif

                    @if($story->hasPaidChapters())
                        <div class="premium-badge">Premium</div>
                    @endif

                </div>

                <div class="story-content">

                    <div class="story-title">
                        {{ Str::limit($story->title,40) }}
                    </div>

                    <div class="story-author">
                        {{ __('ui.by') }} {{ $story->user->username ?? 'Unknown' }}
                    </div>

                    <div class="story-meta">
                        <span>👁 {{ $story->views ?? 0 }}</span>
                        <span>💬 {{ $story->comments_count ?? 0 }}</span>
                        <span>⭐ {{ $story->rating ?? 0 }}</span>
                    </div>

                </div>

            </a>

        @endforeach

    </div>
</div>

{{-- BEST ONGOING --}}
<div class="section">

    <div class="section-header">
        <h2>📘 {{ __('ui.best_ongoing') }}</h2>
        <a href="{{ route('browse.ongoing') }}" class="view-all">{{ __('ui.view_all') }} →</a>
    </div>

    <div class="story-grid">

        @foreach($ongoingStories as $story)

            <a href="{{ route('reader.storydetail', $story->id) }}" class="story-card">

                <div class="story-image">

                    @if($story->cover_image)
                        <img src="{{ $story->cover_image_url }}">
                    @else
                        <div class="story-placeholder">📚</div>
                    @endif

                    @if($story->hasPaidChapters())
                        <div class="premium-badge">Premium</div>
                    @endif

                </div>

                <div class="story-content">

                    <div class="story-title">
                        {{ Str::limit($story->title,40) }}
                    </div>

                    <div class="story-author">
                        {{ __('ui.by') }} {{ $story->user->username ?? 'Unknown' }}
                    </div>

                    <div class="story-meta">
                        <span>👁 {{ $story->views ?? 0 }}</span>
                        <span>💬 {{ $story->comments_count ?? 0 }}</span>
                        <span>⭐ {{ $story->rating ?? 0 }}</span>
                    </div>

                </div>

            </a>

        @endforeach

    </div>
</div>

{{-- FOOTER --}}
<div class="footer">

    <div class="footer-grid">

        <div>
            <div class="footer-logo-circle">
                <img src="{{ asset('images/logo.png') }}" class="footer-pk-logo-img">
            </div>
            <h4>Pakka</h4>
            <p>{{ __('ui.footer_about') }}</p>
        </div>

        <div>
            <h4>{{ __('ui.footer_discover') }}</h4>
            <a href="{{ route('browse') }}">{{ __('ui.browse') }}</a>
            <a href="{{ route('browse.trending') }}">{{ __('ui.trending') }}</a>
            <a href="{{ route('writer.dashboard') }}">{{ __('ui.write') }}</a>
        </div>

        <div>
            <h4>{{ __('ui.footer_help') }}</h4>
            <a href="{{ route('privacy') }}">{{ __('ui.footer_privacy') }}</a>
            <a href="{{ route('terms') }}">{{ __('ui.footer_terms') }}</a>
            <a href="{{ route('community') }}">{{ __('ui.community') }}</a>
            <a href="{{ route('copyright') }}">{{ __('ui.copyright') }}</a>
            <p>{{ __('ui.footer_contact') }}: pakkasupport@gmail.com</p>
        </div>

    </div>

</div>

@endsection