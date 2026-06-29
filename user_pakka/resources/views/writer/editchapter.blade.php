@extends('layout.master')

@section('content')

<title>Write or Edit Chapter</title>
<link rel="stylesheet" href="{{ asset('css/writer/editchapter.css') }}">
<script>
    window.chapterData = {
        isEditing: {{ isset($chapter->id) ? 'true' : 'false' }},
        storyId: {{ $story->id }},
        chapterId: {{ $chapter->id ?? 'null' }},
        storyLanguage: "{{ strtolower($story->language) }}"
    };
</script>

<script src="{{ asset('js/writer/editchapter.js') }}" defer></script>

<div class="container">

@if(session('success'))
    <div class="alert">
        {{ session('success') }}
    </div>
@endif

@if(isset($story) && isset($chapter))

    <a class="back-link" href="{{ route('writer.chapter', $story->id) }}">
        ← {{ __('ui.back') }}
    </a>

    <h2>{{ $story->title }}</h2>

    <div class="card">

        @if($chapter->chapter_approval_status === 'approved' && $chapter->story_progress === 'published')
            <div class="alert">
                {{ __('ui.edit_desc') }}
            </div>
        @endif

        {{-- CREATE / UPDATE FORM --}}
        @if($chapter->id)

            <form id="chapterForm"
                  action="{{ route('writer.chapter.update', [$story->id, $chapter->id]) }}"
                  method="POST">

                @csrf
                @method('PUT')

        @else

            <form id="chapterForm"
                  action="{{ route('writer.chapter.store', $story->id) }}"
                  method="POST">

                @csrf

        @endif

            {{-- TITLE --}}
            <label>
                {{ __('ui.chapter_title') }}
                <span class="required">*</span>
            </label>

            <input type="text"
                   name="title"
                   id="chapterTitle"
                   value="{{ old('title', $chapter->title) }}"
                   placeholder="Enter chapter title"
                   required>

            @error('title')
                <div class="error">{{ $message }}</div>
            @enderror

            {{-- CONTENT --}}
            <label>
                {{ __('ui.chapter_content') }}
                <span class="required">*</span>
            </label>

            <textarea name="content"
                      id="chapterContent"
                      placeholder="Write your chapter here..."
                      required>{{ old('content', $chapter->content) }}</textarea>

            {{-- WORD COUNT --}}
            <div class="word-counter">
                <span id="countLabel">Words:</span>
                <strong id="contentCount">0</strong>
            </div>

            {{-- AUTOSAVE STATUS --}}
            <div class="autosave-status" id="autosaveStatus">
                {{ __('ui.autosave_enabled') }}
            </div>

            @error('content')
                <div class="error">{{ $message }}</div>
            @enderror

            @php
                $chapterCount = $story->chapters()->count();
                
            @endphp
            {{-- PREMIUM --}}
            @if($chapter->chapter_number > 5)

            <div class="card premium-card">
                <div>
                    <h4>{{ __('ui.premium_chapter') }}</h4>
                    <p>{{ __('ui.premium_chapter_desc') }}</p>
                </div>

                <label class="switch">
                    <input type="checkbox"
                        name="is_premium"
                        id="premiumToggle"
                        {{ old('is_premium', $chapter->is_premium) ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </div>

            <div id="priceBox" class="price-box" style="display:none;">
                <div class="price-header">
                    {{ __('ui.premium_price') }}
                </div>

                <div class="price-value">
                    $0.25
                </div>
            </div>

            @else

            <div class="price-box">
                <div class="price-header">
                    {{ __('ui.premium_locked') }}
                </div>

                <div class="price-value">
                    {{ __('ui.free_chapters_required') }}
                </div>

                <small>
                   {{ __('ui.publish_5_chapters_required') }}
                </small>
            </div>

            @endif

            {{-- SAVE --}}
            <button type="submit">

                {{ $chapter->exists ? __('ui.update_chapter') : __('ui.draft_chapter') }}

            </button>

        </form>

    </div>

@endif

</div>

<!-- SUCCESS MODAL UPDATE CHAPTER -->
@if(session('updated'))

<div id="successModal" class="modal-overlay">

    <div class="modal-box">

        <h3>{{ __('ui.chapter_updated') }}</h3>

        <p>{{ __('ui.chapter_updated_successfully') }}</p>

        <div class="modal-actions">

            <button
                onclick="window.location.href='{{ route('writer.chapter', $story->id) }}'">
                {{ __('ui.view_all_chapters') }}
            </button>

        </div>
  
    </div>

</div>

@endif

<!-- VALIDATION MODAL -->
<div id="validationModal" class="modal-overlay" style="display:none;">

    <div class="modal-box">

        <h3 id="validationTitle">{{ __('ui.premium_requirement') }}</h3>

        <p id="validationMessage"></p>

        <div class="modal-actions">

            <button type="button" id="closeValidationModal">
                {{ __('ui.ok') }}
            </button>

        </div>

    </div>

</div>

@endsection