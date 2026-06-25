@extends('layout.master')

@section('content')

<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Create Story</title>
<link rel="stylesheet" href="{{ asset('css/writer/createstory.css') }}">
<script src="{{ asset('js/writer/createstory.js') }}" defer></script>

<div class="wrapper">

    <div class="back">
        <a href="{{ route('writer.dashboard') }}">
            ← {{ __('ui.back_to_dashboard') }}
        </a>
    </div>

    <h1>{{ __('ui.create_new_story') }}</h1>

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
                        {{ __('ui.basic_info') }}
                        <span class="required">*</span>
                    </h3>

                    {{-- TITLE --}}
                    <label class="field-label">
                        {{ __('ui.story_title') }}
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
                        {{ __('ui.synopsis') }}
                        <span class="required">*</span>
                    </label>

                    <textarea name="synopsis"
                              id="synopsisInput"
                              placeholder="{{ __('ui.write_synopsis') }}">{{ old('synopsis') }}</textarea>

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
                        {{ __('ui.language') }}
                        <span class="required">*</span>
                    </label>

                    <select name="language"
                            id="languageSelect"
                            required>

                        <option value="" disabled selected>
                            {{ __('ui.select_language') }}
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
                        {{ __('ui.genres') }}
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

                    <h3>{{ __('ui.tags') }}</h3>

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
                        {{ __('ui.publishing_format') }}
                        <span class="required">*</span>
                    </h3>

                    <div class="format-grid">

                        <label class="format-card active">

                            <input type="radio"
                                   name="format"
                                   value="serialized"
                                   checked
                                   hidden>

                            <h4>📚 {{ __('ui.serialized') }}</h4>

                            <p>{{ __('ui.release_chapter_by_chapter') }}</p>

                        </label>

                    </div>

                </div>

                {{-- Content Warning --}}
                <div class="card">

                    <h3>{{ __('ui.content_warning') }}</h3>

                    <div class="warning-item">
                        <input type="checkbox" name="warnings[]" value="AI-Assisted Content">

                        <div class="warning-content">
                            <h4>{{ __('ui.ai_assisted_content') }}</h4>
                            <p>
                                {{ __('ui.ai_assisted_content_desc') }}
                            </p>
                        </div>
                    </div>

                    <div class="warning-item">
                        <input type="checkbox" name="warnings[]" value="AI-Generated Content">

                        <div class="warning-content">
                            <h4>{{ __('ui.ai_generated_content') }}</h4>
                            <p>
                                {{ __('ui.ai_generated_content_desc') }}
                            </p>
                        </div>
                    </div>

                    <div class="warning-item">
                        <input type="checkbox" name="warnings[]" value="Graphic Violence">

                        <div class="warning-content">
                            <h4>{{ __('ui.graphic_violence') }}</h4>
                            <p>
                                {{ __('ui.graphic_violence_desc') }}
                            </p>
                        </div>
                    </div>

                    <div class="warning-item">
                        <input type="checkbox" name="warnings[]" value="Profanity">

                        <div class="warning-content">
                            <h4>{{ __('ui.profanity') }}</h4>
                            <p>
                                {{ __('ui.profanity_desc') }}
                            </p>
                        </div>
                    </div>

                    <div class="warning-item">
                        <input type="checkbox" name="warnings[]" value="Sensitive Content">

                        <div class="warning-content">
                            <h4>{{ __('ui.sensitive_content') }}</h4>
                            <p>
                                {{ __('ui.sensitive_content_desc') }}
                            </p>
                        </div>
                    </div>

                    <div class="warning-item">
                        <input type="checkbox" name="warnings[]" value="Sexual Content">

                        <div class="warning-content">
                            <h4>{{ __('ui.sexual_content') }}</h4>
                            <p>
                                {{ __('ui.sexual_content_desc') }}
                            </p>
                        </div>
                    </div>

                </div>
                
                {{-- OWNERSHIP --}}
                <div class="card">

                    <h3>{{ __('ui.content_ownership_verification') }}</h3>

                    <div class="warning-item">

                        <input type="checkbox"
                               name="is_fanfiction"
                               value="1"
                               id="fanfictionCheck"
                               {{ old('is_fanfiction') ? 'checked' : '' }}>

                        <div class="warning-content">

                            <h4>{{ __('ui.fanfiction_title') }}</h4>

                            <p>
                                {{ __('ui.fanfiction_desc') }}
                            </p>

                        </div>

                    </div>

                    <div class="note-box">

                        <strong>{{ __('ui.note') }}:</strong>

                        {{ __('ui.fanfiction_note') }}

                    </div>

                </div>

            </div>

            {{-- RIGHT --}}
            <div class="right">

                {{-- COVER --}}
                <div class="card">

                    <h3>{{ __('ui.cover_image') }}</h3>

                    <input type="file"
                           name="cover_image"
                           id="coverInput"
                           accept="image/*">

                </div>

                {{-- LIVE PREVIEW --}}
                <div class="card">

                    <h3>{{ __('ui.live_preview') }}</h3>

                    <img id="previewCover"
                         class="preview-img"
                         src="https://via.placeholder.com/300x400?text=No+Cover">

                    <p>
                        <strong>{{ __('ui.title') }}:</strong>
                        <span id="previewTitle">-</span>
                    </p>

                    <p>
                        <strong>{{ __('ui.language') }}:</strong>
                        <span id="previewLanguage">-</span>
                    </p>

                    <p>
                        <strong>{{ __('ui.format') }}:</strong>
                        <span id="previewFormat">Serialized</span>
                    </p>

                    <p>
                        <strong>{{ __('ui.status') }}:</strong>
                        {{ __('ui.draft') }}
                    </p>

                </div>

                {{-- ACTIONS --}}
                <div class="actions">

                    <button type="submit"
                            name="action"
                            value="draft"
                            class="btn">

                        {{ __('ui.save_draft') }}

                    </button>

                    <button type="submit"
                            name="action"
                            value="next"
                            class="btn btn-dark">

                        {{ __('ui.next') }} →

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>
@endsection