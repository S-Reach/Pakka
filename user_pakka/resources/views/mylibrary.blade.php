@extends('layout.master')

@section('content')

<title>My Library</title>
<link rel="stylesheet" href="{{ asset('css/mylibrary.css') }}">
<script src="{{ asset('js/mylibrary.js') }}" defer></script>

<div class="container">

    <h1>{{ __('ui.my_library') }}</h1>

    <!-- TABS -->
    <div class="tabs">

        <div class="tab active"
             onclick="showTab('saved', this)">
            {{ __('ui.saved') }} ({{ $savedStories->count() }})
        </div>

        <div class="tab"
             onclick="showTab('history', this)">
            {{ __('ui.reading_history') }} ({{ $readingHistory->count() }})
        </div>

    </div>

    <!-- ===================== -->
    <!-- SAVED STORIES -->
    <!-- ===================== -->
    <div id="saved-tab">

        <div class="stories-grid">

            @forelse($savedStories as $library)

                @php
                    $story = $library->story;
                @endphp

                <a href="{{ route('story.show', $story->id) }}" class="card-link">

                    <div class="card">

                        <div class="card-image">

                            @if($story->hasPaidChapters())
                                <div class="premium-tag">Premium</div>
                            @endif

                            @if($story->cover_image)
                                <img src="{{ $story->cover_image_url }}">
                            @else
                                <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; background:#e5e7eb;">
                                    <div style="font-size:50px;">📚</div>
                                </div>
                            @endif

                        </div>

                        <div class="card-body">

                            <div class="card-title">
                                {{ $story->title }}
                            </div>

                            <div class="card-author">
                                by {{ $story->user->username }}
                            </div>

                            <div class="meta">
                                👁 {{ number_format($story->views) }}
                                ❤️ {{ $story->likes()->count() }}
                                ⭐ {{ $story->rating }}
                            </div>

                        </div>

                    </div>

                </a>

            @empty

                <div class="empty">
                    {{ __('ui.no_saved_stories') }}
                </div>

            @endforelse

        </div>

    </div>

    <!-- ===================== -->
    <!-- READING HISTORY -->
    <!-- ===================== -->
    <div id="history-tab" style="display:none;">

        <div class="stories-grid">

            @forelse($readingHistory as $history)

                @php
                    $story = $history->story;
                @endphp

                <a href="{{ route('story.show', $story->id) }}" class="card-link">

                    <div class="card history-card">

                        <div class="card-image">

                            @if($story->cover_image)
                                <img src="{{ $story->cover_image_url }}">
                            @else
                                <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; background:#e5e7eb;">
                                    <div style="font-size:50px;">📚</div>
                                </div>
                            @endif

                            @if($story->hasPaidChapters())
                                <div class="premium-tag">Premium</div>
                            @endif

                        </div>

                        <div class="card-body">

                            <div class="card-title">
                                {{ $story->title }}
                            </div>

                            <div class="card-author">
                                by {{ $story->user->username }}
                            </div>

                            <div class="meta">
                                👁 {{ number_format($story->views) }}
                                ❤️ {{ $story->likes()->count() }}
                                ⭐ {{ $story->rating }}
                            </div>

                        </div>

                    </div>

                </a>

            @empty

                <div class="empty">
                    {{ __('ui.no_reading_history') }}
                </div>

            @endforelse

        </div>

    </div>

</div>

@endsection