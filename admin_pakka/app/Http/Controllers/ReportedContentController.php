<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StoryReport;
use App\Models\ChapterReport;
use App\Models\CommentReport;
use App\Models\Story;
use App\Models\Chapter;
use App\Models\Comment;

class ReportedContentController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->type;
        $status = $request->status;
        $language = $request->language;

        /*
        |----------------------------------
        | STORY REPORTS
        |----------------------------------
        */
        $storyReports = StoryReport::with(['story.user', 'user'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($language, fn($q) => $q->whereHas('story', fn($q2) =>
                $q2->where('language', $language)
            ))
            ->get()
            ->groupBy('story_id')
            ->map(function ($group) {

                $sorted = $group->sortByDesc('created_at')->values();
                $first = $sorted->first();

                return [
                    'id' => $first->story->id,
                    'type' => 'story',
                    'title' => $first->story->title ?? 'Unknown Story',
                    'author' => $first->story->user->name ?? 'Unknown',
                    'language' => $first->story->language ?? 'Unknown',

                    'report_count' => $group->count(),
                    'payment_status' => $first->story->chapters()
                        ->where('is_premium', 1)
                        ->exists()
                            ? 'Paid'
                            : 'Free',

                    // latest report determines status
                    'status' => $first->status,
                    'is_hidden' => $first->story->is_hidden ?? false,

                    // IMPORTANT: newest reporter first
                    'reported_by_list' => $sorted->pluck('user.name')->unique()->values(),

                    'report_reason' => $group->pluck('reason')->unique()->implode(', '),

                    'details' => $first->details ?? '-',
                    'preview' => $first->story->synopsis ?? '',

                    'created_at' => $first->created_at,

                    // 🔥 REOPEN LOGIC (safe before + new report exists)
                    'is_new_report_after_safe' =>
                        $group->where('status', 'pending')->count() > 0 &&
                        $group->where('status', 'safe')->count() > 0,

                    'update_route' => route('reports.update', [
                        'type' => 'story',
                        'id' => $first->story_id
                    ]),

                    'notify_route' => route('reports.notify', [
                        'type' => 'story',
                        'id' => $first->story_id
                    ]),

                    'hide_route' => route('reports.hide',[
                    'type' => 'story',
                        'id' => $first->story_id
                    ]),

                    'unhide_route' => route('reports.unhide',[
                        'type' => 'story',
                        'id' => $first->story_id
                    ]),
                ];
            })
            ->sortByDesc('created_at')
            ->values();

        /*
        |----------------------------------
        | CHAPTER REPORTS
        |----------------------------------
        */
        $chapterReports = ChapterReport::with(['chapter.story.user', 'user'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->get()
            ->groupBy('chapter_id')
            ->map(function ($group) {

                $sorted = $group->sortByDesc('created_at')->values();
                $first = $sorted->first();

                return [
                    'id' => $first->chapter->id,
                    'type' => 'chapter',
                    'title' => $first->chapter->story->title ?? 'Unknown Story',
                    'chapter_title' => $first->chapter->title ?? 'Unknown Chapter',
                    'author' => $first->chapter->story->user->name ?? 'Unknown',

                    'report_count' => $group->count(),
                    'payment_status' => $first->chapter->is_premium
                        ? 'Paid'
                        : 'Free',

                    'status' => $first->status,
                    'is_hidden' => $first->chapter->is_hidden ?? false,

                    'reported_by_list' => $sorted->pluck('user.name')->unique()->values(),
                    'report_reason' => $group->pluck('reason')->unique()->implode(', '),

                    'details' => $first->details ?? '-',
                    'preview' => $first->chapter->content ?? '',

                    'created_at' => $first->created_at,

                    'is_new_report_after_safe' =>
                        $group->where('status', 'pending')->count() > 0 &&
                        $group->where('status', 'safe')->count() > 0,

                    'update_route' => route('reports.update', [
                        'type' => 'chapter',
                        'id' => $first->chapter_id
                    ]),

                    'notify_route' => route('reports.notify', [
                        'type' => 'chapter',
                        'id' => $first->chapter_id
                    ]),

                    'hide_route' => route('reports.hide',[
                    'type' => 'chapter',
                        'id' => $first->chapter_id
                    ]),

                    'unhide_route' => route('reports.unhide',[
                        'type' => 'chapter',
                        'id' => $first->chapter_id
                    ]),
                ];
            })
            ->sortByDesc('created_at')
            ->values();

        /*
        |----------------------------------
        | COMMENT REPORTS
        |----------------------------------
        */
        $commentReports = CommentReport::with(['comment.user', 'user'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->get()
            ->groupBy('comment_id')
            ->map(function ($group) {

                $sorted = $group->sortByDesc('created_at')->values();
                $first = $sorted->first();

                return [
                    'id' => $first->comment->id,
                    'type' => 'comment',
                    'title' => $first->comment->story->title ?? 'Unknown Story',
                    'author' => $first->comment->user->name ?? 'Unknown',

                    'report_count' => $group->count(),
                    'status' => $first->status,
                    'is_hidden' => $first->comment->is_hidden ?? false,

                    'reported_by_list' => $sorted->pluck('user.name')->unique()->values(),
                    'report_reason' => $group->pluck('reason')->unique()->implode(', '),

                    'details' => $first->details ?? '-',
                    'preview' => $first->comment->comment ?? '',

                    'created_at' => $first->created_at,

                    'is_new_report_after_safe' =>
                        $group->where('status', 'pending')->count() > 0 &&
                        $group->where('status', 'safe')->count() > 0,

                    'update_route' => route('reports.update', [
                        'type' => 'comment',
                        'id' => $first->comment_id
                    ]),

                    'hide_route' => route('reports.hide',[
                    'type' => 'comment',
                        'id' => $first->comment_id
                    ]),

                    'unhide_route' => route('reports.unhide',[
                        'type' => 'comment',
                        'id' => $first->comment_id
                    ]),
                ];
            })
            ->sortByDesc('created_at')
            ->values();

        /*
        |----------------------------------
        | MERGE
        |----------------------------------
        */
        $allReports = collect()
            ->merge($storyReports)
            ->merge($chapterReports)
            ->merge($commentReports)
            ->when($type, fn($c) => $c->where('type', ucfirst($type)))
            ->sortByDesc('created_at');

        /*
        |----------------------------------
        | PAGINATION (manual)
        |----------------------------------
        */
        $perPage = 10;
        $page = request()->get('page', 1);

        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $allReports->forPage($page, $perPage),
            $allReports->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        /*
        |----------------------------------
        | COUNTS
        |----------------------------------
        */
        $pendingCount = $allReports->where('status', 'pending')->count();
        $storyCount = $allReports->where('type', 'Story')->count();
        $chapterCount = $allReports->where('type', 'Chapter')->count();
        $commentCount = $allReports->where('type', 'Comment')->count();

        return view('reportedcontent', [
            'allReports' => $paginated,
            'pendingCount' => $pendingCount,
            'storyCount' => $storyCount,
            'chapterCount' => $chapterCount,
            'commentCount' => $commentCount,
        ]);
    }

    public function updateReportStatus(Request $request, $type, $id)
    {
        $status = $request->status;

        if($type == 'story')
        {
            StoryReport::where(
                'story_id',
                $id
            )->update([
                'status' => $status
            ]);
        }

        elseif($type == 'chapter')
        {
            ChapterReport::where(
                'chapter_id',
                $id
            )->update([
                'status' => $status
            ]);
        }

        elseif($type == 'comment')
        {
            CommentReport::where(
                'comment_id',
                $id
            )->update([
                'status' => $status
            ]);
        }

        return back()->with(
            'success',
            'Report updated successfully.'
        );
    }

    public function hideContent($type, $id)
    {
        if ($type == 'story') {

            Story::findOrFail($id)
                ->update([
                    'is_hidden' => true
                ]);

            StoryReport::where(
                'story_id',
                $id
            )->update([
                'status' => 'actioned'
            ]);
        }

        elseif ($type == 'chapter') {

            Chapter::findOrFail($id)
                ->update([
                    'is_hidden' => true
                ]);

            ChapterReport::where(
                'chapter_id',
                $id
            )->update([
                'status' => 'actioned'
            ]);
        }

        elseif ($type == 'comment') {

            Comment::findOrFail($id)
                ->update([
                    'is_hidden' => true
                ]);

            CommentReport::where(
                'comment_id',
                $id
            )->update([
                'status' => 'actioned'
            ]);
        }

        return back()->with(
            'success',
            'Content hidden successfully.'
        );
    }

    public function unhideContent($type, $id)
    {
        if ($type == 'story') {

            Story::findOrFail($id)
                ->update([
                    'is_hidden' => false
                ]);

            StoryReport::where(
                'story_id',
                $id
            )->update([
                'status' => 'safe'
            ]);
        }

        elseif ($type == 'chapter') {

            Chapter::findOrFail($id)
                ->update([
                    'is_hidden' => false
                ]);

            ChapterReport::where(
                'chapter_id',
                $id
            )->update([
                'status' => 'safe'
            ]);
        }

        elseif ($type == 'comment') {

            Comment::findOrFail($id)
                ->update([
                    'is_hidden' => false
                ]);

            CommentReport::where(
                'comment_id',
                $id
            )->update([
                'status' => 'safe'
            ]);
        }

        return back()->with(
            'success',
            'Content restored successfully.'
        );
    }

    public function notifyWriter($type, $id)
    {
        if ($type == 'story') {

            $story = Story::findOrFail($id);

            $story->user->notify(new \App\Notifications\ReportRevisionNotification($story));
        }

        elseif ($type == 'chapter') {

            $chapter = Chapter::with('story')->findOrFail($id);

            $chapter->story->user->notify(new \App\Notifications\ReportRevisionNotification($chapter));
        }

        return back()->with('success', 'Writer notified successfully.');
    }
}