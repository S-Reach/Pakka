@extends('layout.master')

@section('content')

<title>Edit Story</title>
<link rel="stylesheet" href="{{ asset('css/writer/editstory.css') }}">
<script src="{{ asset('js/writer/editstory.js') }}" defer></script>

<div class="wrapper">

    <h1>Edit Story</h1>

    <form action="{{ route('writer.story.update', $story->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="container">

            <!-- LEFT -->
            <div class="left">

                <div class="card">
                    <h3>Basic Info</h3>

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
                    <h3>Genres</h3>

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
                    <h3>Tags</h3>

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
                    <!-- AI Assisted -->
                    <div class="warning-item">
                        <input type="checkbox" name="warnings[]" value="AI-Assisted Content">

                        <div class="warning-content">
                            <h4>AI-Assisted Content</h4>
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
                            <h4>AI-Generated Content</h4>
                            <p>
                                This story was generated using an AI tool and then reviewed or edited by the author.
                                The core narrative may come from AI assistance.
                            </p>
                        </div>
                    </div>

                    <!-- Graphic Violence -->
                    <div class="warning-item">
                        <input type="checkbox" name="warnings[]" value="Graphic Violence">

                        <div class="warning-content">
                            <h4>Graphic Violence</h4>
                            <p>
                                Contains detailed and intense depictions of violence, blood, injury, or brutality
                                that may be disturbing to some readers.
                            </p>
                        </div>
                    </div>

                    <!-- Profanity -->
                    <div class="warning-item">
                        <input type="checkbox" name="warnings[]" value="Profanity">

                        <div class="warning-content">
                            <h4>Profanity</h4>
                            <p>
                                Includes frequent or strong language, swear words, or offensive expressions
                                used throughout the story.
                            </p>
                        </div>
                    </div>

                    <!-- Sensitive Content -->
                    <div class="warning-item">
                        <input type="checkbox" name="warnings[]" value="Sensitive Content">

                        <div class="warning-content">
                            <h4>Sensitive Content</h4>
                            <p>
                                May include themes such as trauma, addiction, abuse, mental health issues,
                                or other emotionally heavy and potentially triggering topics.
                            </p>
                        </div>
                    </div>

                    <!-- Sexual Content -->
                    <div class="warning-item">
                        <input type="checkbox" name="warnings[]" value="Sexual Content">

                        <div class="warning-content">
                            <h4>Sexual Content</h4>
                            <p>
                                Contains explicit or mature sexual themes, descriptions, or scenes intended
                                for adult audiences only.
                            </p>
                        </div>
                    </div>

                </div>
                
                {{-- OWNERSHIP --}}
                <div class="card">
                    <h3>Ownership</h3>

                    <div class="warning-item">

                        <input type="checkbox"
                            name="is_fanfiction"
                            value="1"
                            {{ old('is_fanfiction', $story->is_fanfiction) ? 'checked' : '' }}>

                        <div class="warning-content">
                            <h4>This is fan fiction</h4>
                            <p>Check this if your story uses existing characters or world.</p>
                        </div>

                    </div>
                </div>
            </div>

            <!-- RIGHT (PREVIEW) -->
            <div class="right">

                <div class="card">
                    <h3>Cover Image</h3>

                    <input type="file" name="cover_image" id="coverInput" accept="image/*">
                </div>

                <div class="card">
                    <h3>Live Preview</h3>

                    <img id="previewCover"
                         class="preview-img"
                         src="{{ $story->cover_image ? asset('storage/'.$story->cover_image) : 'https://via.placeholder.com/300x400' }}">

                    <p><strong>Title:</strong> <span id="previewTitle">{{ $story->title }}</span></p>

                    <p><strong>Language:</strong> <span id="previewLanguage">{{ $story->language }}</span></p>

                    <p><strong>Format:</strong> <span id="previewFormat">{{ ucfirst($story->format) }}</span></p>

                    <p><strong>Status:</strong> {{ ucfirst($story->status) }}</p>
                </div>

            </div>

            <div class="actions">
                    <button type="submit" class="btn btn-dark">
                        Update Story
                    </button>
                </div>
        </div>

    </form>

</div>


@endsection