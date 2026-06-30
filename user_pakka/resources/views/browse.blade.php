@extends('layout.master')

@section('content')

<head>
    <title>{{ __('ui.browse_library') }}</title>
    <link rel="stylesheet" href="{{ asset('css/browse.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Battambang:wght@300;400;700&display=swap" rel="stylesheet">
</head>

<div class="browse-container">

    <!-- HEADER -->
    <div class="browse-header">

        <div class="title-area">
            <h1>{{ __('ui.browse_library') }}</h1>
            <p>{{ __('ui.description') }}</p>
        </div>

        <!-- SEARCH -->
        <div class="search-box">
            <form action="{{ route('browse') }}" method="GET">

                <span class="search-icon">⌕</span>

                <input
                    type="text"
                    name="search"
                    placeholder="{{ __('ui.search_placeholder') }}"
                    value="{{ request('search') }}"
                >

            </form>
        </div>

    </div>

    <!-- FILTER FORM -->
    <form action="{{ route('browse') }}" method="GET">

        <input type="hidden" name="search" value="{{ request('search') }}">

        <!-- GENRE -->
        <div class="genre-box">

            <div class="genre-grid">

                <button type="submit" name="genre" value=""
                    class="genre-btn {{ request('genre') == '' ? 'active' : '' }}">
                    All
                </button>

                @php
                    $genres = [
                    __('ui.action'),
                    __('ui.adventure'),
                    __('ui.comedy'),
                    __('ui.drama'),
                    __('ui.fantasy'),
                    __('ui.mystery'),
                    __('ui.romance'),
                    __('ui.horror'),
                    __('ui.sci_fi'),
                    __('ui.thriller'),
                    __('ui.contemporary'),
                    __('ui.historical'),
                    __('ui.psychological'),
                    __('ui.tragedy'),
                    __('ui.satire'),
                    __('ui.urban_fantasy'),
                    __('ui.dark_fantasy'),
                    __('ui.supernatural'),
                    __('ui.crime'),
                    __('ui.slice_of_life'),
                    __('ui.war'),
                    __('ui.sports'),
                    __('ui.western'),
                    __('ui.mythology'),
                ];
                @endphp

                @foreach($genres as $genre)
                    <button type="submit" name="genre" value="{{ $genre }}"
                        class="genre-btn {{ request('genre') == $genre ? 'active' : '' }}">
                        {{ $genre }}
                    </button>
                @endforeach

            </div>
        </div>

        <!-- BOTTOM BAR -->
        <div class="bottom-bar">

            <div class="story-count">
                {{ __('ui.showing_stories', ['count' => $stories->count()]) }}
            </div>

            <div class="sort-area">

                <select name="sort" class="sort-select" onchange="this.form.submit()">

                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>
                        {{ __('ui.latest') }}
                    </option>

                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>
                        {{ __('ui.oldest') }}
                    </option>

                </select>

            </div>

        </div>

    </form>

    <!-- STORIES -->
    @if($stories->count() > 0)

        <div class="grid">

            @foreach($stories as $story)

                <a href="{{ route('reader.storydetail', $story->id) }}"
                    style="text-decoration:none; color:inherit;">

                    <div class="card">

                        <div class="card-image">
                            @if($story->hasPaidChapters())
                                <div class="premium-tag">
                                    {{ __('ui.premium') }}
                                </div>
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
                                {{ $story->user->username }}
                            </div>

                            <div class="meta">
                                👁 {{ number_format($story->views) }}
                                ❤️ {{ $story->likes()->count() }}
                                ⭐ {{ $story->rating }}
                            </div>

                        </div>

                    </div>

                </a>

            @endforeach

        </div>

    @else

        <div class="no-data">
            {{ __('ui.no_results') }}
        </div>

    @endif

</div>

@endsection