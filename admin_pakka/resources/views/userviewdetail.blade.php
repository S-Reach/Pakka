@extends('layout.master')

@section('content')

<link rel="stylesheet" href="{{ asset('css/userviewdetail.css') }}">

<div class="container">

    <!-- HEADER -->
    <div class="page-header">
        <div class="page-title">User Detail</div>
        <a href="javascript:history.back()" class="back-btn">← Back</a>
    </div>

    <div class="grid">

        <!-- LEFT -->
        <div class="profile-card">

            <div class="avatar">
                {{ strtoupper(substr($user->name,0,1)) }}
            </div>

            <h2>{{ $user->name }}</h2>
            <p style="color:#6b7280;">{{ $user->email }}</p>

            <p style="font-size:13px;color:#9ca3af;">
                ID: {{ $user->user_id }}
            </p>

            <span class="status {{ $user->status }}">
                {{ $user->status }}
            </span>

            <hr style="margin:15px 0;">

            <p><strong>Warnings:</strong> {{ $user->warnings }}</p>
            <p><strong>Joined:</strong> {{ $user->created_at->format('M d, Y') }}</p>

        </div>

        <!-- RIGHT -->
        <div>

            <!-- STATS -->
            <div class="card">
                <div class="section-title">Story Overview</div>

                <div class="stats">

                    <div class="stat-box">
                        <div class="stat-number">{{ $user->stories()->count() }}</div>
                        <div class="stat-label">Total Stories</div>
                    </div>

                    <div class="stat-box">
                        <div class="stat-number">{{ $user->stories()->where('story_progress','published')->count() }}</div>
                        <div class="stat-label">Published</div>
                    </div>

                    <div class="stat-box">
                        <div class="stat-number">{{ $user->stories()->where('story_progress','draft')->count() }}</div>
                        <div class="stat-label">Draft</div>
                    </div>

                </div>
            </div>

            <!-- PAYMENT -->
            <div class="card">
                <div class="section-title">Earnings & Payments</div>

                <div class="payment-grid">

                    <div class="payment-box">
                        <div class="payment-amount">${{ $user->total_earnings ?? 0 }}</div>
                        <div class="payment-label">Total Earned</div>
                    </div>

                    <div class="payment-box">
                        <div class="payment-amount">${{ $user->pending_payout ?? 0 }}</div>
                        <div class="payment-label">Pending</div>
                    </div>

                    <div class="payment-box">
                        <div class="payment-amount">${{ $user->paid_payout ?? 0 }}</div>
                        <div class="payment-label">Paid</div>
                    </div>

                </div>
            </div>

            <!-- ACTIONS -->
            <div class="card">
                <div class="section-title">Actions</div>

                <div class="actions">
                    <button class="btn btn-warn">Warn User</button>
                    <button class="btn btn-suspend">Suspend</button>
                    <button class="btn btn-activate">Activate</button>
                </div>
            </div>

            <!-- STORIES (PAGINATED) -->
            <div class="card">
                <div class="section-title">Recent Stories</div>

                @foreach($stories as $story)
                    <div style="padding:10px;border-bottom:1px solid #eee;">
                        <strong>{{ $story->title }}</strong><br>
                        <span style="color:#6b7280;font-size:13px;">
                            {{ $story->story_progress }}
                        </span>
                    </div>
                @endforeach

                <!-- PAGINATION -->
                <div class="simple-pagination">
                    {{ $stories->onEachSide(0)->links('pagination::bootstrap-5') }}
                </div>
            </div>

        </div>

    </div>

</div>

@endsection