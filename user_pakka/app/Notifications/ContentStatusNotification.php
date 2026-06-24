<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ContentStatusNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $status;
    protected $rejection_highlights;

    public function __construct($message, $status, $rejection_highlights = null)
    {
        $this->message = $message;
        $this->status = $status;
        $this->rejection_highlights = $rejection_highlights;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message,
            'status' => $this->status,
            'rejection_highlights' => $this->rejection_highlights,
        ];
    }
}