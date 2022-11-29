<?php

declare(strict_types=1);

namespace App\Mail\Reports;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Collection;

class BatteryExpiryMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly Collection $defibs)
    {
    }

    public function build(): BatteryExpiryMail
    {
        return $this->markdown('mail.reports.battery-expiry-mail', ['defibs' => $this->defibs])->subject('Battery Expiry Report');
    }
}
