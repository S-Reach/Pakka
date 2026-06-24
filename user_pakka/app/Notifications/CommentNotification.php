<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CommentNotification extends Notification
{
    use Queueable;

    protected $story;
    protected $commentUser;

    public function __construct($story, $commentUser)
    {
        $this->story = $story;
        $this->commentUser = $commentUser;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'story_id' => $this->story->id,
            'story_title' => $this->story->title,
            'message' => $this->commentUser->name . ' commented on your story.',
            'user_id' => $this->commentUser->id,
        ];
    }
}