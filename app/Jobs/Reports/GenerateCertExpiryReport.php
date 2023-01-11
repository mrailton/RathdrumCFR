<?php

declare(strict_types=1);

namespace App\Jobs\Reports;

use App\Mail\Reports\CertExpiryMail;
use App\Models\Member;
use App\Traits\GetsReportRecipients;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class GenerateCertExpiryReport implements ShouldQueue
{
    use Dispatchable;
    use GetsReportRecipients;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public string $key = 'cfr_cert_expiry';

    public function handle(): void
    {
        $members = $this->getMembers();
        $recipients = $this->getRecipients();

        foreach ($recipients as $recipient) {
            Mail::to($recipient->user->email)->queue(new CertExpiryMail($members));
        }
    }

    public function getMembers(): Collection
    {
        return Member::query()
            ->where('cfr_certificate_expiry', '<', now()->addMonths(2))
            ->get();
    }
}
