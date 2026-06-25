@extends('layout.master')

@section('content')

<title>Edit Story</title>
<link rel="stylesheet" href="{{ asset('css/writer/editstory.css') }}">
<script src="{{ asset('js/writer/editstory.js') }}" defer></script>

<div class="wrapper">

    <h1>{{ __('ui.edit_story') }}</h1>

    <form action="{{ route('writer.story.update', $story->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="container">

            <!-- LEFT -->
            <div class="left">

                <div class="card">
                    <h3>{{ __('ui.basic_info') }}</h3>

                    <input type="text" name="title" id="titleInput"
                           value="{{ $story->title }}" required>

                    <textarea name="synopsis">{{ $story->synopsis }}</textarea>

                    <select name="language" id="languageSelect">
                        <option value="English" {{ $story->language == 'English' ? 'selected' : '' }}>English</option>
                        <option value="Khmer" {{ $story->language == 'Khmer' ? 'selected' : '' }}>Khmer</option>
                    </select>
                </div>

                <!-- GENRES -->
                <div class="card">
                    <h3>{{ __('ui.genres') }}</h3>

                    <div class="tag-container">
                        @foreach($genres as $genre)
                        <label class="tag-item">
                            <input type="checkbox" name="genres[]" value="{{ $genre }}"
                                {{ in_array($genre, $story->genres ?? []) ? 'checked' : '' }}>
                            <span>{{ $genre }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- TAGS -->
                <div class="card">
                    <h3>{{ __('ui.tags') }}</h3>

                    <div class="tag-container">
                        @foreach($tags as $tag)
                        <label class="tag-item">
                            <input type="checkbox" name="tags[]" value="{{ $tag }}"
                                {{ in_array($tag, $story->tags ?? []) ? 'checked' : '' }}>
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
                    <!-- AI Assisted -->
                    <div class="warning-item">
                        <input type="checkbox" name="warnings[]" value="AI-Assisted Content">

                        <div class="warning-content">
                            <h4>{{ __('ui.ai_assisted_content') }}</h4>
                            <p>
                                This story was written by the author but may include AI support for editing,
                                proofreading, or improving language while keeping the original creative idea intact.
                            </p>
                        </div>
                    </div>

                    <!-- AI Generated -->
                    <div class="warning-item">
                        <input type="checkbox" name="warnings[]" value="AI-Generated Content">

                        <div class="warning-content">
                            <h4>{{ __('ui.ai_generated_content') }}</h4>
                            <p>
                                {{ __('ui.ai_generated_content_desc') }}
                            </p>
                        </div>
                    </div>

                    <!-- Graphic Violence -->
                    <div class="warning-item">
                        <input type="checkbox" name="warnings[]" value="Graphic Violence">

                        <div class="warning-content">
                            <h4>{{ __('ui.graphic_violence') }}</h4>
                            <p>
                                {{ __('ui.graphic_violence_desc') }}
                            </p>
                        </div>
                    </div>

                    <!-- Profanity -->
                    <div class="warning-item">
                        <input type="checkbox" name="warnings[]" value="Profanity">

                        <div class="warning-content">
                            <h4>{{ __('ui.profanity') }}</h4>
                            <p>
                                {{ __('ui.profanity_desc') }}
                            </p>
                        </div>
                    </div>

                    <!-- Sensitive Content -->
                    <div class="warning-item">
                        <input type="checkbox" name="warnings[]" value="Sensitive Content">

                        <div class="warning-content">
                            <h4>{{ __('ui.sensitive_content') }}</h4>
                            <p>
                                {{ __('ui.sensitive_content_desc') }}
                            </p>
                        </div>
                    </div>

                    <!-- Sexual Content -->
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
                    <h3>{{ __('ui.ownership') }}</h3>

                    <div class="warning-item">

                        <input type="checkbox"
                            name="is_fanfiction"
                            value="1"
                            {{ old('is_fanfiction', $story->is_fanfiction) ? 'checked' : '' }}>

                        <div class="warning-content">
                            <h4>{{ __('ui.fanfiction_title') }}</h4>
                            <p>{{ __('ui.fanfiction_desc') }}</p>
                        </div>

                    </div>
                </div>
            </div>

            <!-- RIGHT (PREVIEW) -->
            <div class="right">

                <div class="card">
                    <h3>{{ __('ui.cover_image') }}</h3>

                    <input type="file" name="cover_image" id="coverInput" accept="image/*">
                </div>

                <div class="card">
                    <h3>{{ __('ui.live_preview') }}</h3>

                    <img id="previewCover"
                         class="preview-img"
                         src="{{ $story->cover_image ? asset('storage/'.$story->cover_image) : 'https://via.placeholder.com/300x400' }}">

                    <p><strong>{{ __('ui.title') }}:</strong> <span id="previewTitle">{{ $story->title }}</span></p>

                    <p><strong>{{ __('ui.language') }}:</strong> <span id="previewLanguage">{{ $story->language }}</span></p>

                    <p><strong>{{ __('ui.format') }}:</strong> <span id="previewFormat">{{ ucfirst($story->format) }}</span></p>

                    <p><strong>{{ __('ui.status') }}:</strong> {{ ucfirst($story->status) }}</p>
                </div>

            </div>

            <div class="actions">
                    <button type="submit" class="btn btn-dark">
                        {{ __('ui.update_story') }}
                    </button>
                </div>
        </div>

    </form>

</div>


@endsection