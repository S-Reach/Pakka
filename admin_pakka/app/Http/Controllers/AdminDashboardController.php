<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Story;
use App\Models\StoryReport;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | DASHBOARD COUNTS
        |--------------------------------------------------------------------------
        */

        // Pending stories
        $pendingContent = Story::where(
            'story_approval_status',
            'pending'
        )->count();

        // Pending reports
        $pendingReports = StoryReport::count();

        // Approved today
        $approvedToday = Story::where(
            'story_approval_status',
            'approved'
        )
        ->whereDate('updated_at', today())
        ->count();

        // Total users
        $activeUsers = User::count();

        // Total stories
        $totalContent = Story::count();

        /*
        |--------------------------------------------------------------------------
        | LATEST STORIES
        |--------------------------------------------------------------------------
        */

        $stories = Story::with('user')
            ->latest()
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | LATEST REPORTS
        |--------------------------------------------------------------------------
        */

        $reports = StoryReport::with('user')
            ->latest()
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RECENT ACTIVITIES
        |--------------------------------------------------------------------------
        */

        $activities = collect();

        /*
        |--------------------------------------------------------------------------
        | APPROVED STORIES
        |--------------------------------------------------------------------------
        */

        $approvedStories = Story::with('user')
            ->where('story_approval_status', 'approved')
            ->latest()
            ->take(3)
            ->get();

        foreach ($approvedStories as $story) {

            $activities->push([
                'user' => $story->user->name ?? 'Unknown User',
                'message' => 'Approved story - ' . $story->title,
                'time' => $story->updated_at,
                'icon' => 'fa-solid fa-check',
                'color' => 'green'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | REJECTED STORIES
        |--------------------------------------------------------------------------
        */

        $rejectedStories = Story::with('user')
            ->where('story_approval_status', 'rejected')
            ->latest()
            ->take(3)
            ->get();

        foreach ($rejectedStories as $story) {

            $activities->push([
                'user' => $story->user->name ?? 'Unknown User',
                'message' => 'Rejected story - ' . $story->title,
                'time' => $story->updated_at,
                'icon' => 'fa-solid fa-xmark',
                'color' => 'red'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | REPORT ACTIVITIES
        |--------------------------------------------------------------------------
        */

        $latestReports = StoryReport::with('user')
            ->latest()
            ->take(3)
            ->get();

        foreach ($latestReports as $report) {

            $activities->push([
                'user' => $report->user->name ?? 'Unknown User',
                'message' => 'Reported a story',
                'time' => $report->created_at,
                'icon' => 'fa-regular fa-flag',
                'color' => 'orange'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | SORT ACTIVITIES
        |--------------------------------------------------------------------------
        */

        $activities = $activities
            ->sortByDesc('time')
            ->take(10);

        return view('admindashboard', compact(
            'pendingContent',
            'pendingReports',
            'approvedToday',
            'activeUsers',
            'totalContent',
            'stories',
            'reports',
            'activities'
        ));
    }
}