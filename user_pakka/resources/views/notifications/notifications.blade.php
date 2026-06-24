@extends('layout.master')

@section('content')

<title>Notification</title>
<link rel="stylesheet" href="{{ asset('css/notifications/notifications.css') }}">

<div class="notification-container">

    <!-- HEADER -->
    <div class="notification-header">
        <h1>Notifications</h1>

        <div class="unread-count">
            {{ $unreadCount }} Unread
        </div>
    </div>

    @forelse($notifications as $notification)

        {{-- 🔥 OPTIONAL: CLICK TO MARK AS READ --}}
        <a href="{{ route('notifications.show', $notification->id) }}"
            style="text-decoration:none; color:inherit;">

                <div class="notification-card {{ is_null($notification->read_at) ? 'unread' : 'read' }}">

                    <div class="notification-title">

                        @if(is_null($notification->read_at))
                            <span class="badge badge-new">New</span>
                        @endif

                        @if(isset($notification->data['title']))
                            <span class="notification-heading">
                                {{ $notification->data['title'] }}
                            </span>
                        @endif

                        @if(($notification->data['status'] ?? '') == 'approved')
                            <span class="badge badge-approved">Approved</span>
                        @endif

                        @if(($notification->data['status'] ?? '') == 'rejected')
                            <span class="badge badge-rejected">Rejected</span>
                        @endif

                        @if(($notification->data['status'] ?? '') == 'paid')
                            <span class="badge badge-approved">Paid</span>
                        @endif
                        
                        @if(($notification->data['status'] ?? '') == 'warning')
                            <span class="badge badge-warning">Warning</span>
                        @endif

                        @if(($notification->data['status'] ?? '') == 'reported')
                            <span class="badge badge-rejected">Reported</span>
                        @endif
                    </div>

                    <div class="notification-message">
                        {{ $notification->data['message'] ?? 'No message available' }}
                    </div>
                    
                    <div class="notification-time">
                        {{ $notification->created_at->diffForHumans() }}
                    </div>

                </div>

            </a>

    @empty

        <div class="empty-notification">
            No notifications available.
        </div>

    @endforelse

</div>

@endsection