<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\UserWarningNotification;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where('first_name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%')
                ->orWhere('username', 'like', '%' . $request->search . '%');
        }

        $users = $query->withCount([
            'stories',
            'stories as published_stories' => function ($q) {
                $q->where('story_progress', 'published');
            },
            'stories as draft_stories' => function ($q) {
                $q->where('story_progress', 'draft');
            }
        ])->paginate(5);

        $activeUsers = User::where('status', 'active')->count();
        $warningUsers = User::where('status', 'warning')->count();
        $suspendedUsers = User::where('status', 'suspended')->count();

        return view('usermanagement', compact(
            'users',
            'activeUsers',
            'warningUsers',
            'suspendedUsers'
        ));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        // Paginated stories (IMPORTANT)
        $stories = $user->stories()
            ->latest()
            ->paginate(5);

        return view('userviewdetail', compact('user', 'stories'));
    }

    public function warn($id)
    {
        try {
            $user = User::findOrFail($id);

            $user->status = 'warning';
            $user->warnings = $user->warnings + 1;
            $user->save();

            $user->notify(new UserWarningNotification(
                $user->warnings,
                "You have received warning {$user->warnings}. Please follow community guidelines."
            ));

            return response()->json([
                'message' => 'Warning sent successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function suspend(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $days = $request->input('days', 3); // default 7 days

        $user->status = 'suspended';
        $user->suspended_at = now();
        $user->suspension_days = $days;
        $user->save();

        return response()->json([
            'message' => "User suspended for {$days} days successfully!"
        ]);
    }

    public function activate($id)
    {
        $user = User::findOrFail($id);

        $user->status = 'active';
        $user->warnings = 0;
        $user->suspended_at = null;
        $user->suspension_days = null;
        $user->save();

        return response()->json([
            'message' => 'User activated successfully and warnings reset!'
        ]);
    }
}
