<?php

declare(strict_types=1);

namespace App\Mail\Reports;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CertExpiryMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly Collection $members)
    {
    }

    public function build(): CertExpiryMail
    {
        return $this->markdown('mail.reports.cert-expiry-mail', ['members' => $this->members])->subject('Cert Expiry Report');
    }
}
