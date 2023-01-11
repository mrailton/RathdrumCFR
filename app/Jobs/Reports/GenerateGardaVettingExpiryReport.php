<?php

declare(strict_types=1);

namespace App\Jobs\Reports;

use App\Mail\Reports\GardaVettingExpiryMail;
use App\Models\Member;
use App\Traits\GetsReportRecipients;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class GenerateGardaVettingExpiryReport implements ShouldQueue
{
    use Dispatchable;
    use GetsReportRecipients;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public string $key = 'garda_vetting_expiry';

    public function handle(): void
    {
        $members = $this->getMembers();
        $recipients = $this->getRecipients();

        foreach ($recipients as $recipient) {
            Mail::to($recipient->user->email)->queue(new GardaVettingExpiryMail($members));
        }
    }

    public function getMembers(): Collection
    {
        return Member::query()
            ->where('garda_vetting_date', '<', now()->subYears(3)->addMonths(2))
            ->get();
    }
}
