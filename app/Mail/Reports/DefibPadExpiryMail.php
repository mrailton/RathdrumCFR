<?php

declare(strict_types=1);

namespace App\Mail\Reports;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DefibPadExpiryMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly Collection $defibs)
    {
    }

    public function build(): DefibPadExpiryMail
    {
        return $this->markdown('mail.reports.defib-pad-expiry-mail', ['defibs' => $this->defibs])->subject('Defib Pad Expiry Report');
    }
}
