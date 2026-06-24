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
        ← Back
    </a>

    <h2>{{ $story->title }}</h2>

    <div class="card">

        @if($chapter->chapter_approval_status === 'approved' && $chapter->story_progress === 'published')
            <div class="alert">
                This chapter is published. Editing will resubmit it for admin approval.
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
                Chapter Title
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
                Chapter Content
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
                Autosave enabled
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
                    <h4>Premium Chapter</h4>
                    <p>Readers must unlock this chapter to continue reading.</p>
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
                    Premium Price
                </div>

                <div class="price-value">
                    $0.25
                </div>
            </div>

            @else

            <div class="price-box">
                <div class="price-header">
                    Premium Locked
                </div>

                <div class="price-value">
                    Free Chapters Required
                </div>

                <small>
                    You must publish at least 5 chapters before enabling premium chapters.
                </small>
            </div>

            @endif

            {{-- SAVE --}}
            <button type="submit">

                {{ $chapter->id ? 'Update Chapter' : 'Draft Chapter' }}

            </button>

        </form>

    </div>

@endif

</div>

<!-- SUCCESS MODAL UPDATE CHAPTER -->
@if(session('updated'))

<div id="successModal" class="modal-overlay">

    <div class="modal-box">

        <h3>Chapter Updated</h3>

        <p>Your chapter has been updated successfully.</p>

        <div class="modal-actions">

            <button
                onclick="window.location.href='{{ route('writer.chapter', $story->id) }}'">
                View All Chapters
            </button>

        </div>
  
    </div>

</div>

@endif

<!-- VALIDATION MODAL -->
<div id="validationModal" class="modal-overlay" style="display:none;">

    <div class="modal-box">

        <h3 id="validationTitle">Premium Requirement</h3>

        <p id="validationMessage"></p>

        <div class="modal-actions">

            <button type="button" id="closeValidationModal">
                OK
            </button>

        </div>

    </div>

</div>

@endsection