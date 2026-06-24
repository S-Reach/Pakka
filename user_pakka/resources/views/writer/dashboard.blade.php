@extends('layout.master')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<title>Writer Dashboard</title>
<link rel="stylesheet" href="{{ asset('css/writer/dashboard.css') }}">
<script src="{{ asset('js/writer/dashboard.js') }}" defer></script>

<div class="dashboard-container">

    <!-- HEADER -->
    <div class="dashboard-navbar">
        <div>
            <h1>Writer Dashboard</h1>
            <p>Manage your stories and track performance</p>
        </div>

        <a href="{{ route('writer.createstory') }}" class="btn">+ New Story</a>
    </div>

    <!-- STATS -->
    <div class="stats">

        <div class="stat-card">
            <p>Total Stories</p>
            <h2>{{ $stats['stories'] }}</h2>
        </div>

        <div class="stat-card">
            <p>Published</p>
            <h2>{{ $stats['published'] }}</h2>
        </div>

        <div class="stat-card">
            <p>Drafts</p>
            <h2>{{ $stats['drafts'] }}</h2>
        </div>

        <div class="stat-card">
            <p>Earnings</p>
            <h2>${{ number_format($stats['writerShare'], 2) }}</h2>

            @if($latestWithdrawal && $latestWithdrawal->status == 'pending')
                <button class="btn" disabled
                        style="background:#9ca3af; cursor:not-allowed;">
                    Withdrawal Pending
                </button>
            @else
                <button type="button" class="btn" onclick="openModal()">
                    Request Withdrawal
                </button>
            @endif
        </div>

    </div>

    <!-- FILTER -->
    @php
        $story_progress = request('story_progress', 'all');
        $sort = request('sort', 'latest');
    @endphp

    <div style="display:flex; justify-content:space-between; align-items:center; margin:18px 0;">

        <!-- LEFT: Progress Filters -->
        <div class="filters" style="margin:0;">
            <a href="?story_progress=all&sort={{ $sort }}"
            class="filter {{ $story_progress=='all' ? 'active' : '' }}">All</a>

            <a href="?story_progress=published&sort={{ $sort }}"
            class="filter {{ $story_progress=='published' ? 'active' : '' }}">Published</a>

            <a href="?story_progress=draft&sort={{ $sort }}"
            class="filter {{ $story_progress=='draft' ? 'active' : '' }}">Draft</a>
        </div>

        <!-- RIGHT: Sort Dropdown -->
        <form method="GET" style="margin:0;">
            <input type="hidden" name="story_progress" value="{{ $story_progress }}">

            <select name="sort"
                    onchange="this.form.submit()"
                    style="
                        padding:8px 12px;
                        border-radius:10px;
                        border:1px solid #e5e7eb;
                        background:#fff;
                        font-size:13px;
                        color:#374151;
                        cursor:pointer;">
                <option value="latest" {{ $sort=='latest' ? 'selected' : '' }}>
                    Latest
                </option>
                <option value="oldest" {{ $sort=='oldest' ? 'selected' : '' }}>
                    Oldest
                </option>
            </select>
        </form>

    </div>

    <!-- STORIES -->
    @forelse($stories as $story)

        @if($story_progress == 'all' || $story->story_progress == $story_progress)

        <div class="story {{ $story->story_progress }}">

            <div class="left">

                @if($story->cover_image)
                    <img src="{{ $story->cover_image ? asset('storage/'.$story->cover_image) : 'https://via.placeholder.com/80' }}"
                        class="cover">
                @else
                    <div class="cover">
                        <div style="font-size:45px;">📚</div>
                    </div>
                @endif

                <div>
                    <div class="title">{{ $story->title }}</div>

                    <div>
                        <span class="badge {{ $story->story_progress }}">
                            {{ ucfirst($story->story_progress) }}
                        </span>

                        @if($story->hasPaidChapters())
                            <span class="badge premium">Premium</span>
                        @endif
                    </div>

                    <div class="meta">
                        {{ $story->format }} • 👁 {{ $story->views ?? 0 }} • ❤️ {{ $story->likes()->count() }}
                    </div>
                </div>

            </div>

            <div class="actions">
                <a href="{{ route('writer.story.edit', $story->id) }}">Edit</a>
                <a href="{{ route('writer.chapter', $story->id) }}">Manage</a>
                @if($story->story_status !== 'complete')
                    <form action="{{ route('writer.story.complete', $story->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PATCH')

                        <button type="submit" class="btn">
                            Mark as Complete
                        </button>
                    </form>
                @else
                    <span class="badge published">Completed</span>
                @endif
            </div>

        </div>
        @endif

    @empty

        <div class="empty">
            <h3>No stories yet</h3>
            <a href="{{ route('writer.createstory') }}" class="btn">Create Story</a>
        </div>

    @endforelse
    
    <!--PAGINATION-->
    <div class="pagination-wrapper">
        {{ $stories->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>

</div>

<!-- WITHDRAWAL MODAL -->
<!-- CUSTOM MODAL -->
<div id="withdrawModal" class="modal-overlay">

    <div class="modal-box">

        <div class="modal-header">
            <h2>Request Withdrawal</h2>
            <button class="close-btn" onclick="closeModal()">✕</button>
        </div>

        <form action="{{ route('writer.withdraw.request') }}" method="POST">
            @csrf

            <div class="modal-body">

                <div class="balance-box">
                    <p>Available Balance</p>
                    <h1>${{ number_format($stats['writerShare'], 2) }}</h1>
                </div>

                <div class="form-group">
                    <label>Amount</label>
                    <input type="number" step="0.01" min="10"
                           max="{{ $stats['writerShare'] ?? 0 }}"
                           name="amount" required>
                    <small>Minimum withdrawal: $10.00</small>
                </div>

                <div class="form-group">
                    <label>Bank Name</label>
                    <input type="text" name="bank_name" 
                        placeholder="E.g ABA Bank, ACLEDA or other Bank"required>
                </div>

                <div class="form-group">
                    <label>Account Number</label>
                    <input type="text" name="account_number" 
                        placeholder="Enter Your Bank Account" required>
                </div>

                <div class="form-group">
                    <label>Account Holder Name</label>
                    <input type="text" name="account_holder_name" 
                        placeholder="Full Name as on Your Bank Account" required>
                </div>

            </div>

            <div class="modal-footer">
                <button type="submit">Submit Withdrawal</button>
            </div>

        </form>

    </div>
</div>


@endsection