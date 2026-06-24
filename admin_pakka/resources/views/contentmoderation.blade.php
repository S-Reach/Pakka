@extends('layout.master')

@section('title', 'Content Moderation')

@section('content')
<link rel="stylesheet" href="{{ asset('css/contentmoderation.css') }}">
<script src="{{ asset('js/contentmoderation.js') }}" defer></script>

<!-- TOP HEADER -->
<div class="top-bar">

    <div class="page-title">
        <h1>Content Moderation</h1>
        <p>Review published stories and chapters</p>
    </div>

    <!-- FILTER -->
    <form method="GET" class="filter-form">

        <!-- LANGUAGE -->
        <select name="language"
            class="filter-select"
            onchange="this.form.submit()">

            <option value="">All Languages</option>

            <option value="english"
                {{ request('language') == 'english' ? 'selected' : '' }}>
                English
            </option>

            <option value="khmer"
                {{ request('language') == 'khmer' ? 'selected' : '' }}>
                Khmer
            </option>

        </select>

        <!-- STATUS -->
        <select name="status"
            class="filter-select"
            onchange="this.form.submit()">

            <option value="">All Status</option>

            <option value="pending"
                {{ request('status') == 'pending' ? 'selected' : '' }}>
                Pending
            </option>

            <option value="approved"
                {{ request('status') == 'approved' ? 'selected' : '' }}>
                Approved
            </option>

            <option value="rejected"
                {{ request('status') == 'rejected' ? 'selected' : '' }}>
                Rejected
            </option>

        </select>

        <!-- SORT -->
        <select name="sort"
            class="filter-select"
            onchange="this.form.submit()">

            <option value="latest"
                {{ request('sort') == 'latest' ? 'selected' : '' }}>
                Latest
            </option>

            <option value="oldest"
                {{ request('sort') == 'oldest' ? 'selected' : '' }}>
                Oldest
            </option>

        </select>

    </form>

</div>

<!-- STORIES -->
@forelse($stories as $story)

<div class="story-card">

    <!-- STORY HEADER -->
    <div class="story-header">

        <div class="story-info">

            <h2>
                📚 {{ $story->title }}
            </h2>

            <div class="story-meta">

                <span class="badge badge-gray">
                    Author:
                    {{ $story->user->username ?? 'Unknown' }}
                </span>

                <span class="badge badge-blue">
                    {{ ucfirst($story->language ?? 'Unknown') }}
                </span>

                <span class="badge badge-green">
                    Published
                </span>

            </div>

        </div>

    </div>

    <!-- CHAPTERS -->
    @forelse($story->chapters as $chapter)

    <div class="chapter-card">

        <div class="chapter-left">

            <div class="chapter-title">
                Chapter {{ $chapter->chapter_number }} : {{ $chapter->title }}
            </div>

            <!-- STATUS -->
            @if($chapter->chapter_approval_status == 'pending')

                <span class="badge badge-orange">
                    Pending
                </span>

            @elseif($chapter->chapter_approval_status == 'approved')

                <span class="badge badge-green">
                    Approved
                </span>

            @elseif($chapter->chapter_approval_status == 'rejected')

                <span class="badge badge-red">
                    Rejected
                </span>

                @if($chapter->rejection_reason)

                    <div style="margin-top:10px; color:#dc2626; font-size:14px;">

                        <strong>Reason:</strong>
                        {{ $chapter->rejection_reason }}

                    </div>

                @endif

            @endif

            <!-- PREVIEW -->
            <div class="chapter-preview">

                {{ Str::limit($chapter->content, 150) }}

            </div>

        </div>

        <!-- ACTION -->
        <div class="actions">

            <!-- REVIEW -->
            <button
                class="btn btn-review"
                onclick="window.location.href='{{ route('contentmoderation.show', $chapter->id) }}'">

                Review

            </button>

            <!-- APPROVE -->
            <form method="POST" action="{{ route('contentmoderation.approve', $chapter->id) }}">
                @csrf
                <button type="submit" class="btn btn-approve">
                    Approve
                </button>
            </form>

            <!-- REJECT -->
            <button
                type="button"
                class="btn btn-reject"
                onclick="openRejectModal({{ $chapter->id }}, @js($chapter->content))">

                Reject

            </button>

        </div>

    </div>

    @empty

        <div class="no-chapter">

            No chapters available for moderation.

        </div>

    @endforelse

</div>

@empty

<div class="empty-box">

    <h3>No Published Stories Found</h3>

    <p>
        Stories will appear here after authors publish them.
    </p>

</div>

@endforelse

<div class="simple-pagination">
    {{ $stories->onEachSide(0)->links('pagination::bootstrap-5') }}
</div>

<!-- REJECT MODAL -->
<div id="rejectModal" class="reject-modal">

    <div class="reject-modal-content" style="max-width:900px;">

        <h2 class="reject-title">Reject Content</h2>

        <p class="reject-description">
            Highlight text and add a reason for rejection.
        </p>

        <form id="rejectForm" method="POST">
            @csrf

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">

                <!-- LEFT: CONTENT PREVIEW -->
                <div style="border:1px solid #ddd; border-radius:10px; padding:15px; height:450px; overflow:auto;">
                    <h3>Content Preview</h3>

                    <div id="contentArea"
                         style="white-space:pre-line; line-height:1.6; font-size:15px;">
                    </div>
                </div>

                <!-- RIGHT: COMMENTS -->
                <div>

                    <h3>Rejection Notes</h3>

                    <button type="button"
                        onclick="addHighlightComment()"
                        style="margin:10px 0; padding:8px 12px; background:#2563eb; color:white; border:none; border-radius:6px;">
                        + Add Highlight Comment
                    </button>

                    <div id="commentsBox" style="display:flex; flex-direction:column; gap:10px;"></div>

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

<script>

let rejectionData = [];
let confirmBtn;

document.addEventListener('DOMContentLoaded', function () {
    confirmBtn = document.getElementById('confirmRejectBtn');
    disableRejectButton();
});

function openRejectModal(chapterId, content)
{
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');

    form.action = "/contentmoderation/" + chapterId + "/reject";

    document.getElementById('contentArea').innerText = content;

    modal.style.display = 'flex';

    rejectionData = [];
    document.getElementById('commentsBox').innerHTML = '';
    document.getElementById('rejection_data').value = '';

    disableRejectButton();
}

function closeRejectModal()
{
    document.getElementById('rejectModal').style.display = 'none';
}

function addHighlightComment()
{
    const selection = window.getSelection().toString().trim();

    if (!selection) {
        alert("Please highlight text first.");
        return;
    }

    const comment = prompt("Why reject this?");
    if (!comment) return;

    rejectionData.push({ text: selection, comment });

    renderComments();
    updateHiddenInput();
    updateButtonState();

    window.getSelection().removeAllRanges();
}

function renderComments()
{
    const box = document.getElementById('commentsBox');
    box.innerHTML = '';

    rejectionData.forEach((item, index) => {
        box.innerHTML += `
            <div style="padding:10px;border:1px solid #ddd;border-radius:8px;background:#f9fafb;">
                <b style="color:red;">Text:</b>
                <p>${item.text}</p>

                <b>Reason:</b>
                <p>${item.comment}</p>

                <button type="button" onclick="removeComment(${index})" style="color:red;border:none;background:none;cursor:pointer;">
                    Remove
                </button>
            </div>
        `;
    });
}

function removeComment(index)
{
    rejectionData.splice(index, 1);
    renderComments();
    updateHiddenInput();
    updateButtonState();
}

function updateHiddenInput()
{
    document.getElementById('rejection_data').value =
        JSON.stringify(rejectionData);
}

function updateButtonState()
{
    if (rejectionData.length > 0) {
        enableRejectButton();
    } else {
        disableRejectButton();
    }
}

function enableRejectButton()
{
    confirmBtn.disabled = false;
    confirmBtn.style.background = '#ef4444';
    confirmBtn.style.cursor = 'pointer';
}

function disableRejectButton()
{
    confirmBtn.disabled = true;
    confirmBtn.style.background = '#d1d5db';
    confirmBtn.style.cursor = 'not-allowed';
}

</script>

<script>
function closeRejectModal()
{
    document.getElementById('rejectModal').style.display = 'none';
    document.getElementById('rejection_reason').value = '';
    disableRejectButton();
}

const textarea = document.getElementById('rejection_reason');
const confirmBtn = document.getElementById('confirmRejectBtn');

textarea.addEventListener('input', function () {

    if (textarea.value.trim().length > 0) {
        confirmBtn.disabled = false;
        confirmBtn.style.background = '#ef4444';
        confirmBtn.style.cursor = 'pointer';
    } else {
        disableRejectButton();
    }

});

function disableRejectButton()
{
    confirmBtn.disabled = true;
    confirmBtn.style.background = '#d1d5db';
    confirmBtn.style.cursor = 'not-allowed';
}

</script>

@endsection