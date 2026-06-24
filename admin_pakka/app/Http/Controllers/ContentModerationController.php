<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Models\Chapter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContentModerationController extends Controller
{
    // =========================
    // SHOW STORIES WITH CHAPTER FILTERING
    // =========================
    public function index(Request $request)
    {
        $status = $request->status ?? 'all';

        $stories = Story::with([
            'user',
            'chapters' => function ($query) use ($status, $request) {

                // HIDE DRAFT CHAPTERS
                $query->where('story_progress', '!=', 'draft');

                // STATUS FILTER (pending / approved / rejected / all)
                if ($status !== 'all') {
                    $query->where('chapter_approval_status', $status);
                }

                // SORT CHAPTERS
                $query->orderBy(
                    'updated_at',
                    $request->sort == 'oldest' ? 'asc' : 'desc'
                );
            }
        ])

        // ONLY PUBLISHED STORIES
        ->where('story_progress', 'published')

        // OPTIONAL LANGUAGE FILTER
        ->when($request->language, function ($query) use ($request) {
            $query->where('language', $request->language);
        })

        // ONLY STORIES THAT HAVE VALID CHAPTERS
        ->whereHas('chapters', function ($q) use ($status) {

            $q->where('story_progress', '!=', 'draft');

            if ($status !== 'all') {
                $q->where('chapter_approval_status', $status);
            }
        })

        // GET LATEST CHAPTER DATE FOR SORTING STORIES
        ->withMax(['chapters as latest_chapter_date' => function ($q) use ($status) {

            $q->where('story_progress', '!=', 'draft');

            if ($status !== 'all') {
                $q->where('chapter_approval_status', $status);
            }
        }], 'updated_at')

        // SORT STORIES BY LATEST CHAPTER UPDATE
        ->orderBy(
            'latest_chapter_date',
            $request->sort == 'oldest' ? 'asc' : 'desc'
        )

        ->paginate(5);

        return view('contentmoderation', compact('stories'));
    }

    // =========================
    // VIEW SINGLE CHAPTER
    // =========================
    public function show($id)
    {
        $chapter = Chapter::with('story', 'user')->findOrFail($id);

        return view('contentview', compact('chapter'));
    }

    // =========================
    // APPROVE CHAPTER
    // =========================
    public function approve($id)
    {
        $chapter = Chapter::with('story')->findOrFail($id);

        // ✅ APPROVE CHAPTER
        $chapter->chapter_approval_status = 'approved';
        $chapter->story_progress = 'published'; // 🔥 IMPORTANT FIX
        $chapter->save();

        // ✅ KEEP STORY PUBLISHED
        $story = $chapter->story;
        $story->update([
            'story_progress' => 'published',
            'story_approval_status' => 'approved'
        ]);

        // NOTIFICATION
        DB::table('notifications')->insert([
            'id' => Str::uuid()->toString(),
            'type' => 'ContentStatusNotification',
            'notifiable_type' => User::class,
            'notifiable_id' => $chapter->story->user_id,
            'data' => json_encode([
                'title' => 'Chapter Approval',
                'message' => 'Your chapter has been approved.',
                'status' => 'approved',
                'chapter_id' => $chapter->id,
                'story_id' => $chapter->story->id,
                'chapter_title' => $chapter->title,
                'story_title' => $chapter->story->title,
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // OPTIONAL: update story status
        $story = $chapter->story;
        $story->story_approval_status = 'approved';
        $story->save();

        return redirect()
            ->route('contentmoderation')
            ->with('success', 'Chapter approved successfully!');
    }

    // =========================
    // REJECT CHAPTER
    // =========================
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_highlights' => 'nullable|string|max:1000',
            'rejection_data' => 'nullable|json',
        ]);

        $chapter = Chapter::with('story')->findOrFail($id);

        $chapter->chapter_approval_status = 'rejected';

        // Store rejection highlights
        $chapter->rejection_highlights = $request->rejection_data
            ? json_decode($request->rejection_data, true)
            : null;

        $chapter->save();

        // NOTIFICATION
        $user = User::findOrFail($chapter->story->user_id);

        $user->notifications()->create([
            'id' => Str::uuid(),
            'type' => 'ContentStatusNotification',
            'data' => [
                'title' => 'Chapter Rejected',
                'message' => 'Your chapter has been rejected.',
                'status' => 'rejected',
                'rejection_highlights' => $chapter->rejection_highlights ?? [],
                'story_title' => $chapter->story->title ?? 'Unknown Story',
                'chapter_number' => $chapter->chapter_number ?? 'Unknown Chapter',
                'chapter_title' => $chapter->title ?? 'Unknown Chapter',
                'chapter_id' => $chapter->id,
                'story_id' => $chapter->story->id,
            ],
        ]);

        return redirect()
            ->route('contentmoderation')
            ->with('error', 'Chapter rejected successfully!');
    }
}