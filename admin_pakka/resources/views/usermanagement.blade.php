@extends('layout.master')

@section('content')

<link rel="stylesheet" href="{{ asset('css/usermanagement.css') }}">
<script src="{{ asset('js/usermanagement.js') }}" defer></script>


<div class="container">

    <div class="page-title">
        User Management
    </div>

    <div class="subtitle">
        Manage users and monitor their activity
    </div>

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

    <!-- STATS -->
    <div class="stats">

        <div class="card">
            <div class="stat-title">
                Active Users
            </div>

            <div class="stat-number">
                {{ $activeUsers }}
            </div>
        </div>

        <div class="card">
            <div class="stat-title">
                Users with Warnings
            </div>

            <div class="stat-number">
                {{ $warningUsers }}
            </div>
        </div>

        <div class="card">
            <div class="stat-title">
                Suspended Users
            </div>

            <div class="stat-number">
                {{ $suspendedUsers }}
            </div>
        </div>

    </div>

    <!-- TABLE -->
    <div class="table-card">

        <div class="table-header">

            <form method="GET" action="{{ url()->current() }}">
                <input type="text"
                    name="search"
                    class="search-box"
                    placeholder="Search by name, email, or username..."
                    value="{{ request('search') }}">
            </form>

        </div>

        <table>

            <thead>
                <tr>
                    <th>USER FULL NAME</th>
                    <th>USERNAME</th>
                    <th>STORY STATS</th>
                    <th>JOIN DATE</th>
                    <th>STATUS</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>

            <tbody>

                @foreach($users as $user)

                <tr>

                    <td>
                        <strong>{{ $user->name }}</strong><br>

                        <span class="email">
                            {{ $user->email }}
                        </span><br>

                    </td>

                    <td>
                        {{ $user->username }}
                    </td>

                    <td class="content-stats">

                        Total: {{ $user->stories_count }} <br>

                        <span class="approve">
                            ✔ {{ $user->published_stories }}
                        </span>

                        ×

                        <span class="reject">
                            {{ $user->draft_stories }}
                        </span>

                    </td>

                    <td>
                        {{ $user->created_at->format('M d, Y') }}
                    </td>

                    <td>

                        <span class="status {{ $user->status }}">
                            {{ $user->status }}
                        </span>

                        @if($user->warnings > 0)

                            <span class="warning-text">
                                {{ $user->warnings }} warning(s)
                            </span>

                        @endif

                        {{-- ✅ SUSPENSION INFO --}}
                        @if($user->status == 'suspended')

                            <span class="warning-text" style="color:#c0392b;">
                                Suspended Duration: {{ $user->suspension_days }} day(s)
                            </span>

                            @php
                                $endDate = $user->suspended_at
                                    ? \Carbon\Carbon::parse($user->suspended_at)->addDays($user->suspension_days)
                                    : null;
                            @endphp

                            @if($endDate)
                                <span class="warning-text" style="color:#b91c1c;">
                                    Ends: {{ $endDate->format('M d, Y H:i') }}
                                </span>
                            @endif
                        @endif


                    </td>

                    <td>
                        <div class="actions">

                            <a href="{{ route('users.show', $user->id) }}" class="btn btn-view">
                                View
                            </a>

                            @if($user->status == 'suspended')

                                <button class="btn btn-activate action-btn"
                                        data-id="{{ $user->id }}"
                                        data-action="activate">
                                    Activate
                                </button>

                            @else

                                <button class="btn btn-warn action-btn"
                                        data-id="{{ $user->id }}"
                                        data-action="warn">
                                    Warn
                                </button>

                                <button class="btn btn-suspend action-btn"
                                        data-id="{{ $user->id }}"
                                        data-action="suspend">
                                    Suspend
                                </button>

                            @endif

                        </div>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

<div class="simple-pagination">
    {{ $users->onEachSide(0)->links('pagination::bootstrap-5') }}
</div>

@endsection