@extends('layout.master')
@section('content')

<title>Chapter - {{ $story->title }}</title>
<link rel="stylesheet" href="{{ asset('css/writer/chapter.css') }}">
<script src="{{ asset('js/writer/chapter.js') }}" defer></script>

<div class="wrapper">

    {{-- SUCCESS POPUP --}}
    @if(session('success'))

    <div class="popup-overlay" id="successPopup">

        <div class="popup-box">

            <div class="popup-icon">
                ✅
            </div>

            <h2>
                {{ __('ui.story_submitted') }}
            </h2>

            <p>
                {{ __('ui.chapter_published_successfully') }}
            </p>

            <button onclick="closePopup()">
                {{ __('ui.okay') }}
            </button>

        </div>

    </div>

    @endif

    <div class="back">

        <a href="{{ route('writer.dashboard') }}">
            ← {{ __('ui.back_to_dashboard') }}
        </a>

    </div>

    <div class="page-title">
        {{ $story->title }}
    </div>

    <div class="subtitle">
        {{ __('ui.share_story_world') }}
    </div>

    <div class="card">

        <div class="card-header">

            <div>

                <h2>{{ __('ui.chapters') }}</h2>

                <small>

                    {{ $story->chapters->count() }} chapters •

                    {{ $story->chapters->sum(fn($c) => str_word_count($c->content ?? '')) }}

                    words

                </small>

            </div>

            <a href="{{ route('writer.chapter.create', $story->id) }}"
               class="btn-dark">

               + {{ __('ui.new_chapter') }}

            </a>

        </div>

        @if($story->chapters->count() > 0)

            @foreach($chapters as $chapter)

                <div class="chapter-item">

                    <div>

                        <strong>
                            {{ __('ui.chapter') }} {{ $chapter->chapter_number }}
                        </strong>

                        - {{ $chapter->title ?? 'Untitled' }}

                        <div style="display:flex; gap:8px; margin-top:10px; flex-wrap:wrap;">

                            {{-- STORY PROGRESS --}}
                            <span class="badge
                                {{ $chapter->story_progress == 'published' ? 'badge-published' : 'badge-draft' }}">
                                {{ ucfirst($chapter->story_progress) }}
                            </span>

                            {{-- APPROVAL STATUS --}}
                            <span class="badge
                                @if($chapter->chapter_approval_status == 'approved')
                                    badge-approved
                                @elseif($chapter->chapter_approval_status == 'rejected')
                                    badge-rejected
                                @else
                                    badge-pending
                                @endif
                            ">
                                Approval: {{ ucfirst($chapter->chapter_approval_status) }}
                            </span>

                            @if($chapter->chapter_approval_status == 'rejected' && $chapter->rejection_reason)
                                <div style="margin-top:6px; font-size:13px; color:#b91c1c;">
                                    <strong>Reason:</strong> {{ $chapter->rejection_reason }}
                                </div>
                            @endif

                        </div>

                    </div>

                    <div class="actions">

                        <a href="{{ route('writer.chapter.edit', [$story->id, $chapter->id]) }}">
                            {{ __('ui.edit') }}
                        </a>
                        @if($chapter->story_progress == 'draft')
                            <form action="{{ route('writer.chapter.publish', [$story->id, $chapter->id]) }}"
                                  method="POST"
                                  style="display:inline;">
                                @csrf
                                <button class="publish-btn">
                                    {{ __('ui.publish') }}
                                </button>
                            </form>
                        @endif

                        @if($chapter->chapter_approval_status == 'rejected')
                            <form action="{{ route('writer.chapter.republish', [$story->id, $chapter->id]) }}"
                                method="POST"
                                style="display:inline;">
                                @csrf

                                <button class="publish-btn" style="background:#f59e0b;">
                                    {{ __('ui.resubmit') }}
                                </button>
                            </form>
                        @endif

                    </div>

                </div>

            @endforeach

        @else

            <div class="empty-box">

                <h3>
                    {{ __('ui.no_chapters') }}
                </h3>

                <p>
                    {{ __('ui.start_building_story') }}
                </p>

                <br>

                <a href="{{ route('writer.chapter.create', $story->id) }}"
                   class="btn-dark">

                    {{ __('ui.add_first_chapter') }}

                </a>

            </div>

        @endif

    </div>

    <!--pagination-->
    <div class="pagination-wrapper">
        {{ $chapters->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>


</div>

@endsection