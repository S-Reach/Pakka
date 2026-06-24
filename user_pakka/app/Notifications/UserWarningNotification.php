<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserWarningNotification extends Notification
{
    use Queueable;

    public $warnings;
    public $message;

    public function __construct($warnings, $message)
    {
        $this->warnings = $warnings;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Account Warning',
            'message' => $this->message,
            'warnings' => $this->warnings,
            'status' => 'warning' // ✅ FIXED
        ];
    }
}