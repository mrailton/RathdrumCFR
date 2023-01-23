<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\DefibInspected;
use App\Mail\DefibInspectedMail;
use App\Traits\GetsReportRecipients;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendDefibInspectedMail implements ShouldQueue
{
    use InteractsWithQueue;
    use GetsReportRecipients;

    public string $key = 'defib_inspected';

    public function handle(DefibInspected $event): void
    {
        $recipients = $this->getRecipients();

        foreach ($recipients as $recipient) {
            Mail::to($recipient->user->email)->queue(new DefibInspectedMail($event->defib, $event->inspection));
        }
    }
}
