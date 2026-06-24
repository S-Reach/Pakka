<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MyLibrary;
use App\Models\ReadingHistory;
use Illuminate\Support\Facades\Auth;

class MyLibraryController extends Controller
{
    public function index()
    {
        // =========================
        // SAVED STORIES
        // =========================
        $savedStories = MyLibrary::with([

            'story' => function ($query) {

                $query->where('story_progress', 'published')

                    ->where('story_approval_status', 'approved')

                    ->whereHas('chapters', function ($q) {

                        $q->where('chapter_approval_status', 'approved');

                    });

            },

            'story.user'

        ])

        ->where('user_id', Auth::id())

        // REMOVE INVALID STORIES
        ->whereHas('story', function ($query) {

            $query->where('story_progress', 'published')

                ->where('story_approval_status', 'approved')

                ->whereHas('chapters', function ($q) {

                    $q->where('chapter_approval_status', 'approved');

                });

        })

        ->latest()

        ->get();

        // =========================
        // READING HISTORY
        // =========================
        $readingHistory = ReadingHistory::with([

            'story.user',

            'chapter'

        ])

        ->where('user_id', Auth::id())

        // ONLY APPROVED STORIES
        ->whereHas('story', function ($query) {

            $query->where('story_progress', 'published')

                ->where('story_approval_status', 'approved');

        })

        // ONLY APPROVED CHAPTERS
        ->whereHas('chapter', function ($query) {

            $query->where('chapter_approval_status', 'approved')

                ->where('story_progress', 'published');

        })

        ->latest()

        ->get();

        return view('mylibrary', compact(
            'savedStories',
            'readingHistory'
        ));
    }
}