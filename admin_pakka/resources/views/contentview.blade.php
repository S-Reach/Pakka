@extends('layout.master')

@section('title', 'Content Moderation Detail - Admin Dashboard')

@section('content')

<link rel="stylesheet" href="{{ asset('css/contentview.css') }}">
<script src="{{ asset('js/contentview.js') }}" defer></script>

<div class="container">

    <a href="{{ route('contentmoderation') }}" class="back-link">
        ← Back to Content Moderation
    </a>

    @if(session('success'))
        <div class="alert success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert error">
            {{ session('error') }}
        </div>
    @endif

    <div class="content-card">

        <!-- TOP -->
        <div class="top-section">

            <div class="story-title">
                {{ $chapter->story->title }}
            </div>

            <div class="badge-group">
                <span class="badge blue">
                    Chapter {{ $chapter->chapter_number }} : {{ $chapter->title }}
                </span>

                <span class="badge gray">
                    {{ $chapter->story->type ?? 'Story' }}
                </span>

                <span class="badge blue">
                    {{ $chapter->story->language ?? 'Unknown' }}
                </span>

                @if($chapter->chapter_approval_status == 'pending')

                    <span class="badge orange">
                        Pending
                    </span>

                @elseif($chapter->chapter_approval_status == 'approved')

                    <span class="badge green">
                        Approved
                    </span>

                @elseif($chapter->chapter_approval_status == 'rejected')

                    <span class="badge red">
                        Rejected
                    </span>

                @endif

            </div>

            <div class="info-grid">

                <div class="info-box">
                    <small>Author</small>

                    <strong>
                        {{ $chapter->story->user->username ?? 'Unknown Author' }}
                    </strong>
                </div>

                <div class="info-box">
                    <small>Submitted</small>

                    <strong>
                        {{ $chapter->created_at->format('d M Y') }}
                    </strong>
                </div>

                <div class="info-box">
                    @php
                        $language = strtolower($chapter->story->language ?? '');
                        if ($language === 'khmer') {
                            preg_match_all(
                                '/[\x{1780}-\x{17FF}]/u',
                                $chapter->content,
                                $matches
                            );
                            $count = count($matches[0]);
                            $label = 'Character Count';
                        } else {
                            $englishOnly = preg_replace(
                                '/[\x{1780}-\x{17FF}]/u',
                                ' ',
                                $chapter->content
                            );
                            $count = str_word_count(strip_tags($englishOnly));
                            $label = 'Word Count';
                        }
                    @endphp
                    <small>{{ $label }}</small>
                    <strong>
                        {{ number_format($count) }}
                    </strong>
                </div>

                <div class="info-box">
                    <small>Language</small>

                    <strong>
                        {{ $chapter->story->language ?? 'Unknown' }}
                    </strong>
                </div>

                <div class="info-box">
                    <small>Premium Chapter:</small>
                    @if($chapter->is_premium)
                        <span class="badge orange">Paid Chapter</span>
                    @else
                        <span class="badge green">Free Chapter</span>
                    @endif
                </div>

            </div>

            <div class="action-buttons">

                <!-- APPROVE -->
                <form
                    action="{{ route('contentmoderation.approve', $chapter->id) }}"
                    method="POST">

                    @csrf

                    <button class="btn approve">
                        ✓ Approve Content
                    </button>

                </form>

                <!-- REJECT -->
                <button
                    type="button"
                    class="btn reject"
                    onclick="openRejectModal({{ $chapter->id }})">

                    ✕ Reject Content

                </button>

            </div>

        </div>

        <!-- CONTENT -->
        <div class="content-body">

            <div class="content-title">
                Full Content
            </div>

            <div class="story-content">
                {{ $chapter->content }}
            </div>

            @if(
                $chapter->chapter_approval_status == 'rejected'
                && $chapter->rejection_reason
            )

                <div class="reason-box">

                    <div class="reason-title">
                        Rejection Reason
                    </div>

                    <div>
                        {{ $chapter->rejection_reason }}
                    </div>

                </div>

            @endif

        </div>

    </div>

</div>

<!-- REJECT MODAL -->
<!-- REJECT MODAL (ENHANCED) -->
<div id="rejectModal" class="reject-modal">

    <div class="reject-modal-content" style="max-width:900px;">

        <h2 class="reject-title">Reject & Review Content</h2>

        <p class="reject-description">
            Highlight parts of the content and explain why they are problematic.
        </p>

        <form id="rejectForm" method="POST">
            @csrf

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">

                <!-- LEFT: CONTENT -->
                <div style="border:1px solid #ddd; border-radius:10px; padding:15px; height:500px; overflow:auto;">
                    <h3 style="margin-bottom:10px;">Content Preview</h3>

                    <div id="contentArea"
                         style="white-space:pre-line; line-height:1.6; font-size:15px; cursor:text;">
                        {{ $chapter->content }}
                    </div>
                </div>

                <!-- RIGHT: COMMENTS -->
                <div>

                    <h3 style="margin-bottom:10px;">Rejection Notes</h3>

                    <p style="font-size:13px; color:#6b7280; margin-bottom:10px;">
                        Highlight text → click “Add Comment”
                    </p>

                    <button type="button" onclick="addHighlightComment()"
                        style="margin-bottom:10px; padding:8px 12px; background:#2563eb; color:white; border:none; border-radius:6px;">
                        + Add Highlight Comment
                    </button>

                    <div id="commentsBox" style="display:flex; flex-direction:column; gap:10px;"></div>

                    <!-- hidden input -->
                    <input type="hidden" name="rejection_data" id="rejection_data">

                </div>

            </div>

            <div class="reject-actions" style="margin-top:20px;">
                <button type="button" class="cancel-btn" onclick="closeRejectModal()">CANCEL</button>

                <button type="submit" class="confirm-reject-btn" id="confirmRejectBtn">
                    CONFIRM REJECTION
                </button>
            </div>

        </form>

    </div>

</div>

@endsection