<?php

namespace App\Listeners;

use App\{User, Cheque};
use Carbon\Carbon;
use App\Services\SmsService;
use App\Events\ChequeCreatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSmsNotificationListeners
{
    /**
     * Handle the event.
     *
     * @param  ChequeCreatedEvent  $event
     * @return void
     */
    public function handle(ChequeCreatedEvent $event)
    {
        foreach (User::all() as $user) {
            if (
                $user->isAbleTo('sms-recive')
                && (Cheque::STATUS_DELEVERED == $event->cheque->status)
                && (app('env') == 'production')
            )
            {
                (new SmsService())->sendMessage(
                    $user->phone,
                    sprintf("شيك رقم %s واجب الإستحقاق في الغد", $event->cheque->number),
                    Carbon::parse($event->cheque->due_date)->subDay()
                );
            }
        }
    }
}
