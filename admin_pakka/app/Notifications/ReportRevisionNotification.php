<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\ChapterReport;
use App\Models\StoryReport;

class ReportRevisionNotification extends Notification
{
    use Queueable;

    public $item;

    public function __construct($item)
    {
        $this->item = $item;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $type = class_basename($this->item);

        // =========================
        // CHAPTER
        // =========================
        if ($type === 'Chapter') {

            $reports = ChapterReport::with('user')
                ->where('chapter_id', $this->item->id)
                ->get();

            return [
                'type' => 'revision',
                'title' => 'Chapter Revision Required',
                'message' => 'Your chapter was reported and needs revision.',
                'status' => 'rejected',

                'story_id' => $this->item->story->id ?? null,
                'story_title' => $this->item->story->title ?? '',
                'chapter_id' => $this->item->id,
                'chapter_title' => $this->item->title,

                'report_reason' => $reports->isNotEmpty()
                    ? $reports->pluck('reason')->filter()->unique()->implode(', ')
                    : 'No reason provided',

                'details' => optional($reports->last())->details ?? 'No details provided',

                'report_count' => $reports->count(),

                'reported_by' => $reports->pluck('user.name')
                    ->filter()
                    ->unique()
                    ->values(),
            ];
        }

        // =========================
        // STORY
        // =========================
        if ($type === 'Story') {

            $reports = StoryReport::with('user')
                ->where('story_id', $this->item->id)
                ->get();

            return [
                'type' => 'revision',
                'title' => 'Story Revision Required',
                'message' => 'Your story was reported and needs revision.',
                'status' => 'reported',

                'story_id' => $this->item->id,
                'story_title' => $this->item->title,

                'report_reason' => $reports->isNotEmpty()
                    ? $reports->pluck('reason')->filter()->unique()->implode(', ')
                    : 'No reason provided',

                'details' => optional($reports->last())->details ?? 'No details provided',

                'report_count' => $reports->count(),

                'reported_by' => $reports->pluck('user.name')
                    ->filter()
                    ->unique()
                    ->values(),
            ];
        }

        return [
            'title' => 'Content Revision Required',
            'status' => 'rejected',
        ];
    }
}