<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Story;

class BrowseController extends Controller
{
    // =========================
    // MAIN BROWSE PAGE
    // =========================
    public function browse(Request $request)
    {
        $query = Story::with(['user', 'chapters'])

            // ONLY PUBLISHED STORIES
            ->where('story_progress', 'published')

            // STORY MUST BE APPROVED
            ->where('story_approval_status', 'approved')

            // STORY MUST HAVE AT LEAST ONE APPROVED CHAPTER
            ->whereHas('chapters', function ($q) {
                $q->where('chapter_approval_status', 'approved');
            });

        // =========================
        // SEARCH
        // =========================
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('title', 'LIKE', "%{$search}%")

                  ->orWhereHas('user', function ($userQuery) use ($search) {

                      $userQuery->where('username', 'LIKE', "%{$search}%");

                  });

            });
        }

        // =========================
        // GENRE FILTER
        // =========================
        if ($request->filled('genre') && $request->genre !== 'all') {

            $query->whereJsonContains('genres', $request->genre);

        }

        // =========================
        // SORT FILTER
        // =========================
        if ($request->sort === 'oldest') {

            $query->orderBy('created_at', 'asc');

        } else {

            // DEFAULT = LATEST
            $query->latest();

        }

        // =========================
        // GET STORIES
        // =========================
        $stories = $query->get();

        return view('browse', compact('stories'));
    }

    // =========================
    // OPTIONAL SIMPLE INDEX
    // =========================
    public function index()
    {
        $stories = Story::with(['user', 'chapters'])

            ->where('story_progress', 'published')

            // ONLY APPROVED STORIES
            ->where('story_approval_status', 'approved')

            // MUST HAVE APPROVED CHAPTER
            ->whereHas('chapters', function ($q) {

                $q->where('chapter_approval_status', 'approved');

            })

            ->latest()

            ->get();

        return view('browse', compact('stories'));
    }

    // =========================
    // LATEST STORIES
    // =========================
    public function latest()
    {
        $stories = Story::with([
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
            ->where('story_progress', 'published')
            ->where('story_approval_status', 'approved')
            ->orderByDesc('latest_approved_chapter')
            ->paginate(24)
            ->withQueryString();

        return view('browse', compact('stories'));
    }

    // =========================
    // TRENDING STORIES
    // =========================
    public function trending()
    {
        $stories = Story::with(['user', 'chapters'])
            ->where('story_progress', 'published')
            ->where('story_approval_status', 'approved')
            ->whereHas('chapters', function ($q) {
                $q->where('chapter_approval_status', 'approved');
            })
            ->orderByDesc('views')
            ->paginate(24);

        return view('browse', compact('stories'));
    }

    // =========================
    // COMPLETE STORIES
    // =========================
    public function complete()
    {
        $stories = Story::with(['user', 'chapters'])
            ->where('story_progress', 'published')
            ->where('story_approval_status', 'approved')
            ->where('story_status', 'complete')
            ->whereHas('chapters', function ($q) {
                $q->where('chapter_approval_status', 'approved');
            })
            ->latest()
            ->paginate(24);

        return view('browse', compact('stories'));
    }

    // =========================
    // ONGOING STORIES
    // =========================
    public function ongoing()
    {
        $stories = Story::with(['user', 'chapters'])
            ->where('story_progress', 'published')
            ->where('story_approval_status', 'approved')
            ->where('story_status', 'ongoing')
            ->whereHas('chapters', function ($q) {
                $q->where('chapter_approval_status', 'approved');
            })
            ->latest()
            ->paginate(24);

        return view('browse', compact('stories'));
    }
}