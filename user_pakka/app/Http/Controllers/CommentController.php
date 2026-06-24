<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Chapter;
use App\Models\Story;
use App\Models\CommentReport;
use Illuminate\Support\Facades\Auth;
use App\Notifications\CommentNotification;

class CommentController extends Controller
{
    // STORY COMMENT
    public function storeStoryComment(Request $request, $storyId)
    {
        $request->validate([
            'comment' => 'required|max:1000'
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'story_id' => $storyId,
            'chapter_id' => null,
            'comment' => $request->comment
        ]);

        return back();
    }

    // CHAPTER COMMENT
    public function storeChapterComment(Request $request, $chapterId)
    {
        $request->validate([
            'comment' => 'required|max:1000'
        ]);

        $chapter = Chapter::findOrFail($chapterId);

        Comment::create([
            'user_id' => Auth::id(),
            'story_id' => $chapter->story_id,
            'chapter_id' => $chapter->id,
            'comment' => $request->comment
        ]);

        return back();
    }
    // LIKE COMMENT
    public function like(Comment $comment)
    {
        $user = Auth::user();
        $like = $comment->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            $comment->likes()->create([
                'user_id' => $user->id
        ]);
        $liked = true;
    }
        return response()->json([
            'liked' => $liked,
            'likes' => $comment->likes()->count()
        ]);

    }

    // REPLY COMMENT
    public function reply(Request $request, Comment $comment)
    {
        Comment::create([
            'user_id' => auth()->id(),
            'story_id' => $comment->story_id,
            'chapter_id' => $comment->chapter_id,
            'parent_id' => $comment->id,
            'comment' => $request->comment
        ]);

        return back();
    }

    // REPORT COMMENT
    public function report(Request $request, Comment $comment)
    {
        $request->validate([
            'reason' => 'required|string',
            'details' => 'nullable|string',
        ]);

        $userId = auth()->id();

        // prevent duplicate report
        $exists = CommentReport::where('comment_id', $comment->id)
            ->where('user_id', $userId)
            ->exists();

        if ($exists) {
            return back()->with('error', 'You already reported this comment.');
        }

        CommentReport::create([
            'comment_id' => $comment->id,
            'user_id' => $userId,
            'reason' => $request->reason,
            'details' => $request->details,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Report submitted successfully.');
    }
}
