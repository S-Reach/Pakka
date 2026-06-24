@extends('layout.master')

@section('content')

<title>Detail Notification</title>
<link rel="stylesheet" href="{{ asset('css/notifications/show.css') }}">

<div class="container">

    <h2>
        
        {{ $notification->data['title'] ?? '' }}

        @if(($notification->data['status'] ?? '') == 'approved')
            <span class="badge approved">Approved</span>

        @elseif(($notification->data['status'] ?? '') == 'rejected')
            <span class="badge rejected">Rejected</span>

        @elseif(($notification->data['status'] ?? '') == 'paid')
            <span class="badge paid">Paid</span>
        @elseif(($notification->data['status'] ?? '') == 'warning')
            <span class="badge warning">Warning</span>
        @endif
    </h2>

    @php
        $data = is_array($notification->data)
            ? $notification->data
            : json_decode($notification->data, true);
    @endphp

    <div class="box">

        @if(($data['status'] ?? '') == 'paid')

            <p><b>Payout Amount:</b> ${{ number_format($data['amount'] ?? 0, 2) }}</p>
            <p><b>Status:</b> Paid Successfully</p>
            <p><b>Payment Date:</b> {{ $notification->created_at->format('Y-m-d H:i:s') }}</p>

        @elseif(($data['status'] ?? '') == 'warning')

            <h3>⚠️ Warning Details</h3>

            <p><b>Warning Count:</b><span class="warning-count">{{ $data['warnings'] ?? 0 }}</span></p>
            <p><b>Admin Message:</b>{{ $data['message'] ?? '' }}</p>
            <p><b>Reason:</b>Your account has received a warning because some activities may violate our community guidelines. Please review your content and follow the platform rules to avoid suspension.</p>
            <p><b>Date:</b>{{ $notification->created_at->format('Y-m-d H:i:s') }}</p>

        @elseif(($data['type'] ?? '') == 'revision')
            {{-- ✅ REPORT DETAIL HERE --}}

            <h3>🚨 Report Information</h3>
            
            <p><b>Title:</b> {{ $data['title'] ?? '' }}</p>
            <p><b>Admin Message:</b>{{$data['message' ?? '']}}</p>
            <p><b>Reason:</b> {{ $data['report_reason'] ?? 'No reason provided' }}</p>
            <p><b>Details:</b> {{ $data['details'] ?? 'No details provided' }}</p>
        
        @else

            <p><b>Book Title:</b> {{ $data['story_title'] ?? '' }}</p>

            <p>
                <b>Chapter Number:</b>
                {{ $data['chapter_number'] ?? '' }}

                <b>Chapter Title:</b>
                {{ $data['chapter_title'] ?? '' }}
            </p>

            <p>
                <b>Notification Date:</b>
                {{ $notification->created_at->format('Y-m-d H:i:s') }}
            </p>

        @endif

    </div>

    @if(($data['status'] ?? '') == 'rejected')

        @php
            $highlights = $data['rejection_highlights'] ?? [];
        @endphp

        @if(is_array($highlights) && count($highlights))

            <div class="highlight-section">
                <h3 class="highlight-title">
                    ⚠ Content Revision Required
                </h3>

                @foreach($highlights as $highlight)

                    <div class="highlighted-text">
                        <h4>📌 Highlighted Content</h4>
                        <p>{{ $highlight['text'] ?? 'No text available' }}</p>
                    </div>

                    <div class="moderator-comment">
                        <h4>💬 Moderator Feedback</h4>
                        <p>{{ $highlight['comment'] ?? 'No comment provided' }}</p>
                    </div>

                @endforeach

            </div>

            @if(isset($data['chapter_id']))
                <div style="margin-top:25px; text-align:right;">
                    <a href="{{ route('writer.chapter.edit', [
                        'storyId' => $storyId ?? $data['story_id'] ?? '',
                        'chapterId' => $chapterId ?? $data['chapter_id'] ?? '',
                    ]) }}">
                        <div class="edit-btn">
                            ✏️ Open Chapter & Revise
                        </div>
                    </a>
                </div>
            @endif

        @endif

    @endif

    </div>

</div>

@endsection