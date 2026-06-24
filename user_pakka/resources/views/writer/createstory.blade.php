@extends('layout.master')

@section('content')

<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Create Story</title>
<link rel="stylesheet" href="{{ asset('css/writer/createstory.css') }}">
<script src="{{ asset('js/writer/createstory.js') }}" defer></script>

<div class="wrapper">

    <div class="back">
        <a href="{{ route('writer.dashboard') }}">
            ← Back to Dashboard
        </a>
    </div>

    <h1>Create New Story</h1>

    {{-- SUCCESS --}}
    @if(session('success'))

        <div class="success-box">
            {{ session('success') }}
        </div>

    @endif

    {{-- ERRORS --}}
    @if($errors->any())

        <div class="error-box">

            <ul style="margin:0;padding-left:20px;">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <form action="{{ route('writer.story.store') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        <div class="container">

            {{-- LEFT --}}
            <div class="left">

                {{-- BASIC INFO --}}
                <div class="card">

                    <h3>
                        Basic Info
                        <span class="required">*</span>
                    </h3>

                    {{-- TITLE --}}
                    <label class="field-label">
                        Story Title
                        <span class="required">*</span>
                    </label>

                    <input type="text"
                           name="title"
                           id="titleInput"
                           placeholder="Story Title"
                           value="{{ old('title') }}"
                           required>

                    @error('title')
                        <small class="error-text">
                            {{ $message }}
                        </small>
                    @enderror

                    {{-- SYNOPSIS --}}
                    <label class="field-label">
                        Synopsis
                        <span class="required">*</span>
                    </label>

                    <textarea name="synopsis"
                              id="synopsisInput"
                              placeholder="Write your story synopsis...">{{ old('synopsis') }}</textarea>

                    <div class="word-counter">

                        <span>
                            Recommended: 50 - 500 words
                        </span>

                        <span id="wordCount">
                            0 words
                        </span>

                    </div>

                    @error('synopsis')
                        <small class="error-text">
                            {{ $message }}
                        </small>
                    @enderror

                    {{-- LANGUAGE --}}
                    <label class="field-label">
                        Language
                        <span class="required">*</span>
                    </label>

                    <select name="language"
                            id="languageSelect"
                            required>

                        <option value="" disabled selected>
                            Select Language
                        </option>

                        <option value="English"
                            {{ old('language') == 'English' ? 'selected' : '' }}>
                            English
                        </option>

                        <option value="Khmer"
                            {{ old('language') == 'Khmer' ? 'selected' : '' }}>
                            Khmer
                        </option>

                    </select>

                    @error('language')
                        <small class="error-text">
                            {{ $message }}
                        </small>
                    @enderror

                </div>

                {{-- GENRES --}}
                <div class="card">

                    <h3>
                        Genres
                        <span class="required">*</span>
                    </h3>

                    <div class="tag-container">

                        @foreach($genres as $genre)

                            <label class="tag-item">

                                <input type="checkbox"
                                       name="genres[]"
                                       value="{{ $genre }}"
                                       {{ in_array($genre, old('genres', [])) ? 'checked' : '' }}>

                                <span>{{ $genre }}</span>

                            </label>

                        @endforeach

                    </div>

                    @error('genres')
                        <small class="error-text">
                            {{ $message }}
                        </small>
                    @enderror

                </div>

                {{-- TAGS --}}
                <div class="card">

                    <h3>Tags</h3>

                    <div class="tag-container">

                        @foreach($tags as $tag)

                            <label class="tag-item">

                                <input type="checkbox"
                                       name="tags[]"
                                       value="{{ $tag }}"
                                       {{ in_array($tag, old('tags', [])) ? 'checked' : '' }}>

                                <span>{{ $tag }}</span>

                            </label>

                        @endforeach

                    </div>

                </div>

                {{-- FORMAT --}}
                <div class="card">

                    <h3>
                        Publishing Format
                        <span class="required">*</span>
                    </h3>

                    <div class="format-grid">

                        <label class="format-card active">

                            <input type="radio"
                                   name="format"
                                   value="serialized"
                                   checked
                                   hidden>

                            <h4>📚 Serialized</h4>

                            <p>Release chapter by chapter</p>

                        </label>

                    </div>

                </div>

                {{-- Content Warning --}}
                <div class="card">

                    <h3>Content Warning</h3>

                    <div class="warning-item">
                        <input type="checkbox" name="warnings[]" value="AI-Assisted Content">

                        <div class="warning-content">
                            <h4>AI-Assisted Content</h4>
                            <p>
                                The outline has used an AI tool for editing or proofreading.
                                The story must reflect the author's creativity and storyline,
                                but it may use an AI's voice and tone.
                            </p>
                        </div>
                    </div>

                    <div class="warning-item">
                        <input type="checkbox" name="warnings[]" value="AI-Generated Content">

                        <div class="warning-content">
                            <h4>AI-Generated Content</h4>
                            <p>
                                The story was generated using an AI tool,
                                the author proofread and edited the result.
                            </p>
                        </div>
                    </div>

                    <div class="warning-item">
                        <input type="checkbox" name="warnings[]" value="Graphic Violence">

                        <div class="warning-content">
                            <h4>Graphic Violence</h4>
                            <p>
                                Detailed descriptions of violent acts, bloodshed,
                                mutilation, or disturbing violence.
                            </p>
                        </div>
                    </div>

                    <div class="warning-item">
                        <input type="checkbox" name="warnings[]" value="Profanity">

                        <div class="warning-content">
                            <h4>Profanity</h4>
                            <p>
                                Excessive or obscene swearing and cursing.
                            </p>
                        </div>
                    </div>

                    <div class="warning-item">
                        <input type="checkbox" name="warnings[]" value="Sensitive Content">

                        <div class="warning-content">
                            <h4>Sensitive Content</h4>
                            <p>
                                Depictions of torture, slavery, substance abuse,
                                mental illness, addiction, self-harm,
                                or other sensitive topics.
                            </p>
                        </div>
                    </div>

                    <div class="warning-item">
                        <input type="checkbox" name="warnings[]" value="Sexual Content">

                        <div class="warning-content">
                            <h4>Sexual Content</h4>
                            <p>
                                Explicit sexual content or descriptive scenes.
                            </p>
                        </div>
                    </div>

                </div>
                
                {{-- OWNERSHIP --}}
                <div class="card">

                    <h3>Content Ownership Verification</h3>

                    <div class="warning-item">

                        <input type="checkbox"
                               name="is_fanfiction"
                               value="1"
                               id="fanfictionCheck"
                               {{ old('is_fanfiction') ? 'checked' : '' }}>

                        <div class="warning-content">

                            <h4>This is fan fiction</h4>

                            <p>
                                Check this if your story uses someone else's characters or world.
                            </p>

                        </div>

                    </div>

                    <div class="note-box">

                        <strong>Note:</strong>

                        Fan fiction and derivative works should respect copyright laws.

                    </div>

                </div>

            </div>

            {{-- RIGHT --}}
            <div class="right">

                {{-- COVER --}}
                <div class="card">

                    <h3>Cover Image</h3>

                    <input type="file"
                           name="cover_image"
                           id="coverInput"
                           accept="image/*">

                </div>

                {{-- LIVE PREVIEW --}}
                <div class="card">

                    <h3>Live Preview</h3>

                    <img id="previewCover"
                         class="preview-img"
                         src="https://via.placeholder.com/300x400?text=No+Cover">

                    <p>
                        <strong>Title:</strong>
                        <span id="previewTitle">-</span>
                    </p>

                    <p>
                        <strong>Language:</strong>
                        <span id="previewLanguage">-</span>
                    </p>

                    <p>
                        <strong>Format:</strong>
                        <span id="previewFormat">Serialized</span>
                    </p>

                    <p>
                        <strong>Status:</strong>
                        Draft
                    </p>

                </div>

                {{-- ACTIONS --}}
                <div class="actions">

                    <button type="submit"
                            name="action"
                            value="draft"
                            class="btn">

                        Save Draft

                    </button>

                    <button type="submit"
                            name="action"
                            value="next"
                            class="btn btn-dark">

                        Next →

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>
@endsection