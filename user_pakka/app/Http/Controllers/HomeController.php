<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\MyLibrary;
use App\Models\Story;

class HomeController extends Controller
{
    public function index()
    {
        // =========================
        // LATEST UPDATES
        // =========================
        $latestStories = Story::with([
                'user',
                'chapters' => function ($q) {
                    $q->where('chapter_approval_status', 'approved')
                    ->latest('updated_at');
                }
            ])
            ->withMax([
                'chapters as latest_approved_chapter' => function ($q) {
                    $q->where('chapter_approval_status', 'approved');
                }
            ], 'updated_at')
            ->where('story_approval_status', 'approved')
            ->orderByDesc('latest_approved_chapter')
            ->take(10)
            ->get();

        // =========================
        // TRENDING STORIES
        // =========================
        $trendingStories = Story::with([
                'user',
                'chapters' => function ($q) {
                    $q->where('chapter_approval_status', 'approved')
                      ->latest();
                }
            ])
            ->where('story_approval_status', 'approved')
            ->orderBy('views', 'desc')
            ->take(10)
            ->get();

        // =========================
        // COMPLETED STORIES
        // =========================
        $completedStories = Story::with([
                'user',
                'chapters' => function ($q) {
                    $q->where('chapter_approval_status', 'approved')
                      ->latest();
                }
            ])
            ->where('story_status', 'complete')
            ->where('story_approval_status', 'approved')
            ->latest()
            ->take(10)
            ->get();

        // =========================
        // ONGOING STORIES
        // =========================
        $ongoingStories = Story::with([
                'user',
                'chapters' => function ($q) {
                    $q->where('chapter_approval_status', 'approved')
                      ->latest();
                }
            ])
            ->where('story_status', 'ongoing')
            ->where('story_approval_status', 'approved')
            ->latest()
            ->take(10)
            ->get();

        // =========================
        // CONTINUE READING
        // =========================
        $readingStories = [];

        if (Auth::check()) {
            $readingStories = MyLibrary::with([
                    'story.user',
                    'lastChapter'
                ])
                ->where('user_id', Auth::id())
                ->orderBy('updated_at', 'desc')
                ->take(10)
                ->get();
        }

        return view('home', compact(
            'latestStories',
            'trendingStories',
            'completedStories',
            'ongoingStories',
            'readingStories'
        ));
    }
}