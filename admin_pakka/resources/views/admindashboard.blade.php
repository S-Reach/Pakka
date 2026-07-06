@extends('layout.master')

@section('title', 'Admin Dashboard')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

<div class="page-title">
    <h1>Admin Dashboard</h1>
    <p>Monitor stories, reports, users, and payments from the platform</p>
</div>

<!-- =========================
     STATS
========================= -->

<div class="stats-grid">

    <div class="stat-card">

        <div class="stat-info">
            <p>Pending Stories</p>
            <h2>{{ $pendingContent }}</h2>
        </div>

        <div class="stat-icon orange">
            <i class="fa-regular fa-clock"></i>
        </div>

    </div>

    <div class="stat-card">

        <div class="stat-info">
            <p>Pending Reports</p>
            <h2>{{ $pendingReports }}</h2>
        </div>

        <div class="stat-icon red">
            <i class="fa-regular fa-flag"></i>
        </div>

    </div>

    <div class="stat-card">

        <div class="stat-info">
            <p>Approved Today</p>
            <h2>{{ $approvedToday }}</h2>
        </div>

        <div class="stat-icon green">
            <i class="fa-regular fa-circle-check"></i>
        </div>

    </div>

    <div class="stat-card">

        <div class="stat-info">
            <p>Total Users</p>
            <h2>{{ number_format($activeUsers) }}</h2>
        </div>

        <div class="stat-icon blue">
            <i class="fa-solid fa-users"></i>
        </div>

    </div>

    <div class="stat-card">

        <div class="stat-info">
            <p>Total Stories</p>
            <h2>{{ number_format($totalContent) }}</h2>
        </div>

        <div class="stat-icon blue">
            <i class="fa-regular fa-file-lines"></i>
        </div>

    </div>

</div>

<!-- =========================
     USER STORIES
========================= -->

<div class="dashboard-section">

    <h2 class="section-title">Latest User Stories</h2>

    <table class="dashboard-table">

        <thead>
            <tr>
                <th>User</th>
                <th>Story</th>
                <th>Status</th>
                <th>Created</th>
            </tr>
        </thead>

        <tbody>

            @forelse($stories as $story)

            <tr>

                <td>
                    <div style="display:flex;align-items:center;gap:12px;">

                        @if($story->user && $story->user->avatar)
                            <img src="{{ $story->user->avatar_url }}">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($story->user->name ?? 'User') }}">
                        @endif

                        <span>{{ $story->user->name ?? 'Unknown User' }}</span>
                    </div>
                </td>

                <td>{{ $story->title }}</td>

                <td>
                    <span class="status {{ $story->status }}">
                        {{ ucfirst($story->status) }}
                    </span>
                </td>

                <td>{{ $story->created_at->diffForHumans() }}</td>

            </tr>

            @empty

            <tr>
                <td colspan="4">No stories found.</td>
            </tr>

            @endforelse

        </tbody>

    </table>

</div>

<!-- =========================
     USER REPORTS
========================= -->

<div class="dashboard-section">

    <h2 class="section-title">Latest Reports</h2>

    <table class="dashboard-table">

        <thead>
            <tr>
                <th>User</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Reported</th>
            </tr>
        </thead>

        <tbody>

            @forelse($reports as $report)

            <tr>

                <td>{{ $report->user->name ?? 'Unknown User' }}</td>

                <td>{{ $report->reason }}</td>

                <td>
                    <span class="status {{ $report->status }}">
                        {{ ucfirst($report->status) }}
                    </span>
                </td>

                <td>{{ $report->created_at->diffForHumans() }}</td>

            </tr>

            @empty

            <tr>
                <td colspan="4">No reports found.</td>
            </tr>

            @endforelse

        </tbody>

    </table>

</div>

<!-- =========================
     RECENT ACTIVITY
========================= -->

<div class="activity-box">

    <div class="activity-header">
        <h2>Recent Activity</h2>
    </div>

    @forelse($activities as $activity)

    <div class="activity-item">

        <div class="activity-left">

            <div class="activity-icon {{ $activity['color'] }}">
                <i class="{{ $activity['icon'] }}"></i>
            </div>

            <div class="activity-text">
                <h4>{{ $activity['user'] }}</h4>
                <p>{{ $activity['message'] }}</p>
            </div>

        </div>

        <div class="activity-time">
            {{ $activity['time']->diffForHumans() }}
        </div>

    </div>

    @empty

    <div class="activity-item">
        No recent activity found.
    </div>

    @endforelse

</div>

@endsection
