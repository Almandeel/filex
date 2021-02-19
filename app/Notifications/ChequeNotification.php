<?php

namespace App\Notifications;

use App\Cheque;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class ChequeNotification extends Notification
{
    use Queueable;

    public $cheque;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Cheque $cheque)
    {
        $this->cheque = $cheque;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'شيك رقم ' . $this->cheque->number . ' واجب الإستحقاق في الغد'
            // 'cheque_id' => $this->cheque->id,
            // 'number' => $this->cheque->number,
            // 'due_date' => $this->cheque->due_date,
            // 'benifit' => $this->cheque->getBenefit(),
        ];
    }
}
