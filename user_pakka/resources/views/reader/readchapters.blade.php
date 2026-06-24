@extends('layout.master')

@section('content')

@php
    $pref = auth()->user()?->readingPreference;

    $englishFont = match($pref->font ?? 'sans') {
        'serif' => "Georgia, serif",
        'mono' => "'Courier New', monospace",
        default => "'Segoe UI', Arial, sans-serif"
    };

    $khmerFont = match($pref->khmer_font ?? 'battambang') {
        'noto' => "'Noto Sans Khmer'",
        'kantumruy' => "'Kantumruy Pro'",
        default => "'Battambang'"
    };

    $selectedFont = $story->language === 'Khmer'
    ? $khmerFont
    : $englishFont;
    
@endphp

<link href="https://fonts.googleapis.com/css2?family=Battambang:wght@300;400;700&family=Kantumruy+Pro:wght@100..700&family=Noto+Sans+Khmer:wght@100..900&display=swap" rel="stylesheet">
<title>{{ $story->title }} - {{ __('ui.read_chapter') }} {{ $chapter->title }}</title>
<link rel="stylesheet" href="{{ asset('css/reader/readchapters.css') }}">
<script src="{{ asset('js/reader/readchapters.js') }}" defer></script>

<body
    data-theme="{{ $pref->theme ?? 'light' }}"
    style="
        --font-family: {{ $selectedFont }};
        --font-size: {{ $pref->size ?? 18 }}px;
    "
>

<!-- TOP BAR -->
<div class="top-bar">

    <a class="top-left"
       href="{{ route('reader.storydetail', $story->id) }}">
        ← {{ __('ui.back') }}
    </a>

    <div style="display:flex;align-items:center;gap:18px;">

        <div class="chapter-report"
             onclick="openChapterReportModal()">
            🚩
        </div>

        <a class="settings"
           href="{{ route('preferences') }}">
            ⚙️
        </a>

    </div>

</div>
<!-- CHAPTER REPORT MODAL -->

<div id="chapterReportModal" class="chapter-report-modal">

    <div class="chapter-report-box">

        <div class="chapter-report-header">
            {{ __('ui.report_chapter') }}
        </div>

        <form action="{{ route('chapter.report', $chapter->id) }}" method="POST">

            @csrf

            <label>{{ __('ui.reason') }}</label>

            <select name="reason" required>
                <option value="">{{ __('ui.select_reason') }}</option>
                <option value="Spam">{{ __('ui.spam') }}</option>
                <option value="Harassment">{{ __('ui.harassment') }}</option>
                <option value="Inappropriate Content">{{ __('ui.inappropriate_content') }}</option>
                <option value="Copyright">{{ __('ui.copyright') }}</option>
                <option value="Other">{{ __('ui.other') }}</option>
            </select>

            <label>{{ __('ui.additional_details') }}</label>

            <textarea
                name="details"
                placeholder="{{ __('ui.explain_issue') }}"
            ></textarea>

            <div class="chapter-report-actions">

                <button type="button" class="cancel-btn"
                    onclick="closeChapterReportModal()">

                    {{ __('ui.cancel') }}

                </button>

                <button type="submit" class="submit-btn">
                    {{ __('ui.submit_report') }}
                </button>
            </div>
        </form>
    </div>

</div>

<!-- READING SECTION -->

<div class="container">

    <div class="subtitle">
        {{ $story->title }}
    </div>

    <div class="title">
        Chapter {{ $chapterIndex + 1 }}:
        {{ $chapter->title }}
    </div>

    <div class="author">
        {{ __('ui.by') }} {{ $story->user->username }}
    </div>

    <div class="content">

        {!! nl2br(e($chapter->content)) !!}

    </div>

    <!-- FOOTER -->

    <div class="footer">

        @if($previousChapter)

            <a href="{{ route('reader.readchapters', [$story->id, $previousChapter->id]) }}">

                <button class="btn">
                    ← {{ __('ui.previous') }}
                </button>

            </a>

        @else

            <button class="btn" disabled>
                ← {{ __('ui.previous') }}
            </button>

        @endif

        <div class="center-text">

            {{ __('ui.chapter') }} {{ $chapterIndex + 1 }}
            {{ __('ui.of') }}
            {{ $totalChapters }}

        </div>

        @if($nextChapter)

            <a href="{{ route('reader.readchapters', [$story->id, $nextChapter->id]) }}">

                <button class="btn">
                    {{ __('ui.next') }} →
                </button>

            </a>

        @else

            <button class="btn" disabled>
                {{ __('ui.next') }} →
            </button>

        @endif

    </div>

    <!-- BACK BUTTON -->

    <div class="down-bar">

        <a class="down-left" href="{{ route('reader.storydetail', $story->id) }}">
            ← {{ __('ui.back') }}
        </a>

    </div>

</div>

<!-- SPACE BETWEEN SECTIONS -->

<div class="section-divider"></div>

<!-- COMMENT SECTION -->

<div class="comments-wrapper">

    <div class="comments-container">

        <div class="comments-header">

            <span>
                💬 {{ __('ui.chapter_comments') }} ({{ $chapter->comments->count() ?? 0 }})
            </span>

        </div>

        <!-- COMMENT FORM -->
        @auth
        <form action="{{ route('chapter.comment.store', $chapter->id) }}" method="POST">
            @csrf
            <div class="comment-box">
                <div class="avatar">
                    @if(auth()->user()->avatar)
                        <img
                            src="{{ asset('storage/' . auth()->user()->avatar) }}"
                            style="width:100%;height:100%;object-fit:cover;"
                        >
                    @else
                        {{ strtoupper(substr(auth()->user()->username, 0, 1)) }}

                    @endif
                </div>

                <textarea
                    name="comment"
                    placeholder="{{ __('ui.what_think_chapter') }}"
                    required
                ></textarea>
            </div>

            <div class="comment-action">
                <button type="submit">
                    {{ __('ui.post_comment') }}
                </button>
            </div>
        </form>
        @else
        <div class="no-comments">
            🔒 {{ __('ui.login_to_comment') }}
            <br><br>
            <a href="{{ route('login') }}" class="btn">
                {{ __('ui.login') }}
            </a>
        </div>
        @endauth
        <hr>

        <!-- COMMENT LIST -->
        @if($chapter->comments && $chapter->comments->count())
            @foreach($chapter->comments as $comment)
                <div class="comment-item">
                    <div class="avatar">
                        @if($comment->user->avatar)
                            <img src="{{ asset('storage/' . $comment->user->avatar) }}"
                                 style="width:100%;height:100%;object-fit:cover;">
                        @else

                            {{ strtoupper(substr($comment->user->username ?? 'U', 0, 1)) }}
                        @endif
                    </div>
                    <div class="comment-content">
                        <strong>
                            {{ $comment->user->username }}
                        </strong>
                        <span>
                            {{ $comment->created_at->diffForHumans() }}
                        </span>
                        <p>
                            {{ $comment->comment }}
                        </p>
                    </div>
                </div>

            @endforeach
        @else
            <div class="no-comments">
                💭 {{ __('ui.no_comments_yet') }}
                <br>
                {{ __('ui.be_first_comment') }}
            </div>
        @endif
    </div>
</div>

@endsection