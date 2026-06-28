@extends('layout.master')

@section('content')

<title>{{ $story->title }} - Story Details</title>
<link rel="stylesheet" href="{{ asset('css/reader/storydetail.css') }}">
<script>
    window.APP = {
        storyId: {{ $story->id }},
        userId: {{$story->user_id}},
        liked: {{ $liked ? 'true' : 'false' }},
        isFollowing: {{ $isFollowing ? 'true' : 'false' }},
        csrf: "{{ csrf_token() }}"
    };
</script>
<script src="{{ asset('js/reader/storydetail.js') }}" defer></script>

<div class="container">

    <div class="top-bar">
        <a class="back-link" href="{{ route('browse') }}">← {{ __('ui.back') }}</a>
    </div>

    <div class="content">

        <!-- LEFT -->
        <div class="left">

            <div class="cover">

                @if($story->cover_image)
                    <img src="{{ $story->cover_image ? asset('storage/'.$story->cover_image) : 'https://via.placeholder.com/300x400' }}">
                @else
                    <div class="story-placeholder">📚
                    </div>
                @endif


                @if($story->hasPaidChapters())
                    <div class="free">Premium</div>
                @endif

            </div>

            @if($readingHistory && $readingHistory->chapter_id)

                <a href="{{ route('reader.readchapters', [$story->id, $readingHistory->chapter_id]) }}">
                    <button class="btn black">
                        {{ __('ui.continue_reading') }}
                    </button>
                </a>

            @else

                <a href="{{ route('reader.readchapters', [$story->id, $story->chapters->where('story_progress', 'published')->first()?->id ?? 0]) }}">
                    <button class="btn black">
                        {{ __('ui.start_reading') }}
                    </button>
                </a>

            @endif

            <form action="{{ route('story.library', $story->id) }}" method="POST">
                @csrf
                <button class="btn gray">
                    {{ $isSaved ? '✓ ' . __('ui.in_library') : '+ ' . __('ui.add_library') }}
                </button>
            </form>

            <div class="action-row">
                <button id="likeBtn"
                        class="btn gray"
                        title="{{ $liked ? 'You already liked this story' : 'Click to like' }}">
                    ❤️ <span id="likeText" 
                        data-liked="{{ __('ui.liked') }}" 
                        data-like="{{ __('ui.like') }}">
                        {{ $liked ? __('ui.liked') : __('ui.like') }}
                        </span>
                </button>
                <button class="btn gray" onclick="openSharePopup()">
                    🔗 {{ __('ui.share') }}
                </button>
            </div>

            <!--REPORT STORY-->
            <button class="btn gray" onclick="openReportPopup()">
                🚩 {{ __('ui.report_story') }}
            </button>

            <!-- REPORT POPUP -->
            <div id="reportPopup" class="report-modal">

                <div class="report-box">

                    <!-- HEADER -->
                    <div class="report-header">

                        <div class="report-title">
                            ⚠️ {{ __('ui.report_story') }}
                        </div>

                        <span class="close-btn" onclick="closeReportPopup()">×</span>

                    </div>

                    <p class="report-sub">
                        {{ __('ui.report_text') }}
                    </p>

                    <!-- STORY INFO -->
                    <div class="report-story-box">
                        <small>{{ __('ui.reporting') }}:</small>
                        <strong>{{ $story->title }}</strong>
                    </div>

                    <!-- FORM -->
                    <form action="{{ route('story.report', $story->id) }}" method="POST">
                        @csrf

                        <div class="report-section-title">
                            {{ __('ui.report_reason') }} *
                        </div>

                         <select name="reason" required>
                            <option value="">{{ __('ui.select_reason') }}</option>
                            <option value="Inappropriate content">{{ __('ui.inappropriate_content') }}</option>
                            <option value="Spam or misleading">{{ __('ui.spam_or_misleading') }}</option>
                            <option value="Copyright violation">{{ __('ui.copyright_violation') }}</option>
                            <option value="Plagiarism">{{ __('ui.plagiarism') }}</option>
                            <option value="Harassment or hate speech">{{ __('ui.harassment_or_hate_speech') }}</option>
                            <option value="Violence or graphic content">{{ __('ui.graphic_violence') }}</option>
                            <option value="Other">{{ __('ui.other') }}</option>
                        </select>

                        <div class="report-section-title">
                            {{ __('ui.additional_details') }} ({{ __('ui.optional') }})
                        </div>

                        <textarea name="details"
                                placeholder="{{ __('ui.report_placeholder') }}"
                                rows="4"></textarea>

                        <!-- BUTTONS -->
                        <div class="report-actions">

                            <button type="button"
                                    class="btn-cancel"
                                    onclick="closeReportPopup()">
                                {{ __('ui.cancel') }}
                            </button>

                            <button type="submit"
                                    class="btn-submit">
                                {{ __('ui.submit_report') }}
                            </button>

                        </div>

                    </form>

                </div>

            </div>

            <!-- STATS -->
            <div class="stats">
                <div>
                    <span>{{ __('ui.views') }}</span>
                    <span id="viewCount">{{ $story->views }}</span>
                </div>
                <div>
                    <span>{{ __('ui.likes') }}</span>
                    <span id="likeCount">{{ $story->likes()->count() }}</span>
                </div>
                <div>
                    <span>{{ __('ui.rating') }}</span>
                    <span id="ratingValue">⭐ {{ $story->rating ?? 0 }}</span>
                </div>
                <div>
                    <span>{{ __('ui.chapters') }}</span>
                    <span>{{ $story->chapters->where('story_progress', 'published')->count() }}</span>
                </div>
            </div>

            <!-- RATE BUTTON -->
             <button class="btn gray" onclick="openRating()">
                ⭐ {{ __('ui.rate_this_story') }}
            </button>
            <!-- RATING MODAL Button -->
                <div id="ratingModal" class="rating-modal">

                    <div class="rating-box">

                        <span class="close-btn" onclick="closeRating()">×</span>

                        <h3>{{ __('ui.rate_story') }} "{{ $story->title }}"</h3>

                        <p class="sub-text">
                            {{ __('ui.rate_placeholder') }}
                        </p>

                        <!-- STARS -->
                        <div class="stars" id="stars">
                            <span data-value="1">★</span>
                            <span data-value="2">★</span>
                            <span data-value="3">★</span>
                            <span data-value="4">★</span>
                            <span data-value="5">★</span>
                        </div>

                        <p class="hint">{{ __('ui.click_star_to_rate') }}</p>

                        <!-- CURRENT -->
                        <div class="rating-footer">
                            <div>
                                <small>{{ __('ui.current_rating') }}</small><br>
                                ⭐ {{ $story->rating ?? 0 }}
                            </div>
                            <div>
                                <small>{{ __('ui.total_ratings') }}</small><br>
                                {{ $story->ratings_count ?? 0 }}
                            </div>
                        </div>
                        <button class="btn black" onclick="submitRating()">
                            {{ __('ui.submit_rating') }}
                        </button>
                    </div>
                </div>
            <!-- POPUP to display messages -->
            <div id="popup" class="popup">
                <div class="popup-box">
                    <div class="popup-icon">⭐</div>
                    <div id="popupMessage" class="popup-message"></div>
                    <button onclick="closePopup()" class="popup-btn">OK</button>
                </div>
            </div>
        </div>

        <!-- SHARE POPUP -->
            <div id="sharePopup" class="share-popup">

                <div class="share-box">

                    <!-- CLOSE -->
                    <span class="share-close" onclick="closeSharePopup()">×</span>

                    <h3>
                        {{ __('ui.share') }} "{{ $story->title }}"
                    </h3>

                    <p class="share-sub">
                        {{ __('ui.share_story') }}
                    </p>

                    <label class="share-label">
                        {{ __('ui.story_link') }}
                    </label>

                    <div class="share-input-box">

                        <input
                            type="text"
                            id="shareLink"
                            value="{{ route('story.show', $story->id) }}"
                            readonly>

                        <button onclick="copyShareLink()" class="copy-btn">
                            📋
                        </button>

                    </div>

                </div>

            </div>

        <!-- RIGHT -->
        <div class="right">

            <!-- HEADER -->
            <div class="header-info">

                <div class="tags">

                    @if($story->genres)
                        @foreach($story->genres as $genre)
                            <span>{{ $genre }}</span>
                        @endforeach
                    @endif

                </div>


                <div class="title">
                    {{ $story->title }}
                </div>

                <div class="author-box">

                    <div class="author-left">

                    <!-- AVATAR -->
                    <a href="{{ route('writerprofile', $story->user->id) }}"
                    class="author-link">

                        <div class="avatar">

                            @if($story->user && $story->user->avatar)

                                <img src="{{ asset('storage/'.$story->user->avatar) }}"
                                     style="width:100%;height:100%;object-fit:cover;">

                            @else

                                {{ strtoupper(substr($story->user->name ?? 'A',0,1)) }}

                            @endif

                        </div>

                        <div>

                            <div>
                                <a href="{{ route('writerprofile', $story->user->id) }}" class="author-name">
                                    {{ $story->user->username ?? 'Unknown Author' }}
                                </a>
                            </div>

                            <small style="color:#9ca3af;">
                                {{ $followersCount }} {{ __('ui.followers') }}
                            </small>

                        </div>

                    </div>

                    @if(!auth()->check() || auth()->id() != $story->user_id)
                        <button id="followBtn" class="follow">
                            {{ $isFollowing ? 'Unfollow' : 'Follow' }}
                        </button>
                    @endif

                </div>

                <div class="desc">
                    {{ $story->synopsis }}
                </div>

                @php
                $contentWarningsMap = [
                    'AI-Assisted Content' => 'The outline has used an AI tool for editing or proofreading.',
                    'AI-Generated Content' => 'The story was generated using an AI tool, and edited by the author.',                    
                    'Graphic Violence' => 'Detailed descriptions of violent acts, bloodshed, mutilation, or disturbing violence.',
                    'Profanity' => 'Excessive or obscene swearing and cursing.',                    
                    'Sensitive Content' => 'Depictions of torture, slavery, substance abuse, mental illness, addiction, self-harm, or other sensitive topics.',
                    'Sexual Content' => 'Explicit sexual content or descriptive scenes.',
                ];
                @endphp

               @if(!empty($story->warnings))
                    <div class="info-box warning-box">

                        <div class="info-title">⚠️ {{ __('ui.content_warning') }}</div>

                        <ul style="margin:0;padding-left:18px;">
                            @foreach($story->warnings as $warning)
                                <li>
                                    <strong>{{ $warning }}</strong><br>
                                    <small style="color:#92400e;">
                                        {{ $contentWarningsMap[$warning] ?? '' }}
                                    </small>
                                </li>
                            @endforeach
                        </ul>

                    </div>
                @endif

                @if($story->is_fanfiction)
                    <div class="info-box verified-box">
                        <div class="info-title">📢 {{ __('ui.fanfiction') }}</div>
                    </div>
                @endif

                <div class="updated">
                    ⏱ Updated {{ $story->updated_at }}
                </div>

            </div>

            <!-- CHAPTERS -->
            <div class="chapter-box">

                <div class="chapter-title">
                    {{ __('ui.chapters') }}

                @forelse($story->chapters->where('story_progress', 'published') as $chapter)

                @php
                    // Free for writer
                    $isAuthor = auth()->check() && $story->user_id === auth()->id();
                    // FREE if marked free OR price is 0 / 0.00 / less than 0.01
                    $isFree = $chapter->is_premium || floatval($chapter->price) <= 0;

                    $isPurchased = false;

                    if(auth()->check()) {
                        $isPurchased = \App\Models\ChapterPurchase::where([
                            'user_id' => auth()->id(),
                            'chapter_id' => $chapter->id,
                            'payment_status' => 'paid'
                        ])->exists();
                    }
                @endphp

                <div class="chapter">

                    {{-- FREE OR PURCHASED --}}
                    @if($isFree || $isPurchased || $isAuthor)

                        <a class="chapter-link"
                        href="{{ route('reader.readchapters', [$story->id, $chapter->id]) }}">

                            <strong>
                                {{ __('ui.chapter') }} {{ $chapter->chapter_number }}:
                                {{ $chapter->title }}
                            </strong>

                        </a>

                    {{-- LOCKED --}}
                    @else

                        <strong>
                            🔒 Chapter {{ $chapter->chapter_number }}:
                            {{ $chapter->title }}
                        </strong>

                        <div style="margin-top:10px;">

                            <a href="{{ route('chapter.pay', $chapter->id) }}"
                            class="btn black"
                            style="display:inline-block; padding:8px 14px; text-decoration:none; max-width:100%;">
                                Unlock Chapter (${{ $chapter->price }})
                            </a>

                        </div>

                    @endif

                    <div class="meta">
                        💬 {{ $chapter->comments->count() }}

                        @if(!$isFree)
                            💰 ${{ $chapter->price }}
                        @endif
                    </div>

                </div>

                @empty
                    <p style="padding:15px;">No chapters yet.</p>
                @endforelse

            </div>

            <!-- COMMENTS -->
                <div class="comment-box">

                    <div class="comment-title">
                        {{ __('ui.comments') }} ({{ $story->comments->count() }})
                    </div>

                    <!-- COMMENT FORM -->
                    <form action="{{ route('story.comment', $story->id) }}"
                        method="POST">

                        @csrf

                        <div class="comment-input-area">

                            <div class="comment-avatar">
                                @if(Auth::user() && Auth::user()->avatar)

                                    <img src="{{ asset('storage/'.Auth::user()->avatar) }}"
                                        style="width:100%;height:100%;border-radius:50%;object-fit:cover;">

                                @else

                                    {{ strtoupper(substr(Auth::user()->name ?? 'U',0,1)) }}

                                @endif
                            </div>

                            <div class="comment-form">

                                <textarea
                                    name="comment"
                                    rows="3"
                                    placeholder="Share your thoughts about this story..."
                                    required></textarea>

                                <div class="comment-action">

                                    <button type="submit" class="post-btn">
                                        {{ __('ui.post_comment') }}
                                    </button>

                                </div>

                            </div>

                        </div>

                    </form>

                    <!-- DYNAMIC COMMENTS -->
                    @forelse($story->comments->where('parent_id', null) as $comment)
                        <div class="comment-item">
                            <!-- AVATAR -->
                            <div class="comment-avatar gray-avatar">
                                @if($comment->user->avatar)
                                    <img src="{{ asset('storage/'.$comment->user->avatar) }}"
                                        style="width:100%;height:100%;border-radius:50%;object-fit:cover;">
                                @else
                                    {{ strtoupper(substr($comment->user->name,0,1)) }}
                                @endif
                            </div>
                            <!-- CONTENT -->
                            <div class="comment-content">
                                <div class="comment-user-row">
                                    <strong>
                                        {{ $comment->user->username }}
                                    </strong>
                                    <span>
                                        {{ $comment->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <p>
                                    {{ $comment->comment }}
                                </p>
                                <!-- ACTIONS -->
                                <div class="comment-meta">
                                    <form class="comment-like-form"
                                    data-id="{{ $comment->id }}"
                                    style="display:inline;">
                                    @csrf
                                <button type="button"
                                    class="like-btn"
                                    data-liked="{{ $comment->isLikedByUser() ? '1' : '0' }}"
                                    style="border:none;background:none;cursor:pointer;font-size:14px;">
        
                                <span class="heart">
                                    {{ $comment->isLikedByUser() ? '❤️' : '🤍' }}
                                </span>

                                <span class="like-text" 
                                    data-liked="{{ __('ui.liked') }}" 
                                    data-like="{{ __('ui.like') }}"
                                    style="margin-right: 5px;">
                                {{ $comment->isLikedByUser() ? __('ui.liked') : __('ui.like') }}
                            </span>

        <span class="like-count">
            {{ $comment->likes->count() }}
        </span>
    </button>
</form>
                                    <!-- REPLY BUTTON -->
                                    <span onclick="toggleReply({{ $comment->id }})">
                                        {{ __('ui.reply') }}
                                    </span>
                                    <!-- REPORT -->
                                    <form action="{{ route('comment.report', $comment->id) }}"
                                        method="POST">
                                        @csrf
                                        <button type="button"
                                                onclick="openCommentReportPopup({{ $comment->id }}, `{{ $comment->comment }}`)"
                                                style="border:none;background:none;cursor:pointer;color:red;">
                                            {{ __('ui.report') }}
                                        </button>
                                    </form>
                                </div>

                                <!--COMMENT REPORT POPUP-->
                                <div id="commentReportPopup" class="report-modal">
                                    <div class="report-box">
                                        <!-- HEADER -->
                                        <div class="report-header">
                                            <div class="report-title">
                                                ⚠️ {{ __('ui.report_comment') }}
                                            </div>
                                            <span class="close-btn" onclick="closeCommentReportPopup()">×</span>
                                        </div>
                                        <p class="report-sub">
                                            Help us maintain a safe and respectful community.
                                            Your report will be reviewed by our moderation team.
                                        </p>

                                        <!-- COMMENT INFO -->
                                        <div class="report-comment-box">
                                            <small>Reporting:</small>
                                            <strong>{{ $comment->content }}</strong>
                                        </div>

                                        <!-- FORM -->
                                        <form action="{{ route('comment.report', $comment->id) }}" method="POST">
                                            @csrf

                                            <div class="report-section-title">
                                                Reason for reporting *
                                            </div>

                                            <select name="reason" required>
                                                <option value="">Select Reason</option>
                                                <option value="Spam">Spam</option>
                                                <option value="Harassment or Bullying">Harassment or Bullying</option>
                                                <option value="Hate Speech">Hate Speech</option>
                                                <option value="Inappropriate Content">Inappropriate Content</option>
                                                <option value="Spoilers Without Warning">Spoilers Without Warning</option>
                                                <option value="Other">Other</option>
                                            </select>

                                            <div class="report-section-title">
                                                Additional details (optional)
                                            </div>

                                            <textarea name="details"
                                                    placeholder="Provide any additional information that might help our review team..."
                                                    rows="4"></textarea>

                                            <!-- BUTTONS -->
                                            <div class="report-actions">

                                                <button type="button"
                                                        class="btn-cancel"
                                                        onclick="closeCommentReportPopup()">
                                                    Cancel
                                                </button>

                                                <button type="submit"
                                                        class="btn-submit">
                                                    Submit Report
                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>
                                

                                <!-- REPLY FORM -->
                                <div id="reply-form-{{ $comment->id }}"
                                    style="display:none;margin-top:15px;">
                                    <form action="{{ route('comment.reply', $comment->id) }}"
                                        method="POST">
                                        @csrf
                                        <textarea
                                            name="comment"
                                            rows="2"
                                            placeholder="Write a reply..."
                                            style="width:95%;padding:12px;border-radius:10px;border:1px solid #ddd;"
                                            required></textarea>
                                        <button class="post-btn"
                                                style="margin-top:8px;">
                                            Reply
                                        </button>
                                    </form>
                                </div>

                                <!-- REPLIES -->
                                @foreach($comment->replies as $reply)
                                    <div class="reply-box">
                                        <div class="comment-avatar small-avatar">
                                            @if($reply->user->avatar)
                                                <img src="{{ asset('storage/'.$reply->user->avatar) }}"
                                                    style="width:100%;height:100%;border-radius:50%;object-fit:cover;">
                                            @else
                                                {{ strtoupper(substr($reply->user->name,0,1)) }}
                                            @endif
                                        </div>
                                        <div>
                                            <div class="comment-user-row">
                                                <strong>
                                                    {{ $reply->user->username }}
                                                </strong>
                                                <span>
                                                    {{ $reply->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                            <p>
                                                {{ $reply->comment }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @empty
                        <p>{{ __('ui.no_comments_yet') }}</p>
                        @endforelse
                </div>
        </div>
    </div>
</div>

@endsection