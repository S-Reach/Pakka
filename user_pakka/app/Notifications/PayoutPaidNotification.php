<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class PayoutPaidNotification extends Notification
{
    use Queueable;

    protected $amount;

    public function __construct($amount)
    {
        $this->amount = $amount;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Payout Paid',
            'message' => 'Your withdrawal request of $' . number_format($this->amount, 2) . ' has been paid successfully.',
            'amount' => $this->amount,
        ];
    }
}