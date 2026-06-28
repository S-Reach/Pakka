@extends('layout.master')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Battambang:wght@300;400;700&family=Kantumruy+Pro:wght@100..700&family=Noto+Sans+Khmer:wght@100..900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/preferences.css') }}">
<script src="{{ asset('js/preferences.js') }}" defer></script>

@php

    $pref = auth()->user()?->readingPreference;

    $currentStory = null;
    $isKhmerStory = false;

    if(session('last_story_id')) {
        $currentStory = \App\Models\Story::find(session('last_story_id'));

        if($currentStory && $currentStory->language === 'Khmer') {
            $isKhmerStory = true;
        }
    }

@endphp


<div class="container">

    <!-- BACK BUTTON -->
    @if(session('last_story_id') && session('last_chapter_id'))
        <a href="{{ route('reader.readchapters', [
            session('last_story_id'),
            session('last_chapter_id')
        ]) }}">
            <button class="back-btn">
                ← {{ __('ui.back_to_reading') }}
            </button>
        </a>
    @endif

    <h1>{{ __('ui.reading_preferences') }}</h1>

    <form method="POST" action="{{ route('preferences.save') }}">
        @csrf

        <!-- THEME -->
        <h2>{{ __('ui.theme') }}</h2>
        <div class="options">
            <div id="theme-light"
                 class="card theme-card {{ $preferences->theme == 'light' ? 'active' : '' }}"
                 onclick="selectOption('theme','light')">
                Light
            </div>

            <div id="theme-sepia"
                 class="card sepia theme-card {{ $preferences->theme == 'sepia' ? 'active' : '' }}"
                 onclick="selectOption('theme','sepia')">
                Sepia
            </div>

            <div id="theme-dark"
                 class="card dark theme-card {{ $preferences->theme == 'dark' ? 'active' : '' }}"
                 onclick="selectOption('theme','dark')">
                Dark
            </div>
        </div>

        <input type="hidden" name="theme" id="theme" value="{{ $preferences->theme }}">

        <!-- FONT -->
        @if($isKhmerStory)

        <h2>{{ __('ui.khmer_font') }}</h2>

        <div class="options">

            <div id="khmer_font-battambang"
                class="card khmer_font-card {{ ($preferences->khmer_font ?? 'battambang') == 'battambang' ? 'active' : '' }}"
                onclick="selectOption('khmer_font','battambang')"
                style="font-family:'Battambang';">
                Battambang
            </div>

            <div id="khmer_font-kantumruy"
                class="card khmer_font-card {{ ($preferences->khmer_font ?? '') == 'kantumruy' ? 'active' : '' }}"
                onclick="selectOption('khmer_font','kantumruy')"
                style="font-family:'Kantumruy Pro';">
                Kantumruy Pro
            </div>

            <div id="khmer_font-noto"
                class="card khmer_font-card {{ ($preferences->khmer_font ?? '') == 'noto' ? 'active' : '' }}"
                onclick="selectOption('khmer_font','noto')"
                style="font-family:'Noto Sans Khmer';">
                Noto Sans Khmer
            </div>

        </div>

        <input type="hidden"
            name="khmer_font"
            id="khmer_font"
            value="{{ $preferences->khmer_font}}">

    @else

        <h2>English Fonts</h2>

        <div class="options">

            <div id="font-sans"
                class="card font-card {{ $preferences->font == 'sans' ? 'active' : '' }}"
                onclick="selectOption('font','sans')">
                Sans
            </div>

            <div id="font-serif"
                class="card font-card {{ $preferences->font == 'serif' ? 'active' : '' }}"
                onclick="selectOption('font','serif')">
                Serif
            </div>

            <div id="font-mono"
                class="card font-card {{ $preferences->font == 'mono' ? 'active' : '' }}"
                onclick="selectOption('font','mono')">
                Mono
            </div>

        </div>

        <input type="hidden"
            name="font"
            id="font"
            value="{{ $preferences->font }}">

    @endif

        <!-- SIZE -->
        <h2>{{ __('ui.font_size') }}</h2>

        <div style="display:flex; align-items:center; gap:10px;">

            <button type="button" onclick="changeSize(-1)" class="size-btn">A-</button>

            <input type="range"
                   min="12"
                   max="30"
                   value="{{ $preferences->size }}"
                   class="slider"
                   name="size"
                   id="size"
                   oninput="updateSize(this.value)">

            <button type="button" onclick="changeSize(1)" class="size-btn">A+</button>

            <span id="sizeLabel">{{ $preferences->size }}px</span>

        </div>

        @if($isKhmerStory)

            <h2>Preview Khmer</h2>

            <div id="preview-khmer" class="preview">
                ខ្ញុំកំពុងសាកល្បងការផ្លាស់ប្ដូរពុម្ពអក្សរខ្មែរ។
                នេះគឺជាអត្ថបទសម្រាប់មើលការផ្លាស់ប្ដូរពុម្ពអក្សរ។
                ការអានសៀវភៅជាភាសាខ្មែរគួរតែមានបទពិសោធន៍ល្អ។
            </div>

        @else

            <h2>Preview English</h2>

            <div id="preview" class="preview">
                The night was dark and full of mysteries.
                Sarah walked through the abandoned library...
            </div>

        @endif

        <button class="btn">
            {{ __('ui.apply_continue_reading') }}
        </button>
    </form>

</div>

<!-- POPUP -->
 @if(session('success'))
    <div id="flash-success" data-message="{{ session('success') }}"></div>
@endif

@if(session('error'))
    <div id="flash-error" data-message="{{ session('error') }}"></div>
@endif

<div id="popup" class="popup">

</div>

@endsection