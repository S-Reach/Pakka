<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // 📄 Show notifications page
    public function index()
    {
        $user = auth()->user();

        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->get();

        $unreadCount = $user->unreadNotifications()->count();

        return view('notifications.notifications', compact(
            'notifications',
            'unreadCount'
        ));
    }

    // ✅ Mark single notification as read
    public function markAsRead($id)
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);

        if (!$notification->read_at) {
            $notification->markAsRead();
        }

        return back();
    }

    // 🔥 NEW: API for live count
    public function unreadCount()
    {
        return response()->json([
            'count' => auth()->user()
                ->unreadNotifications()
                ->count()
        ]);
    }

    // 📄 Show single notification detail page
    public function show($id)
    {
        $notification = auth()->user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        // mark as read when opened
        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        return view('notifications.show', compact('notification'));
    }

}