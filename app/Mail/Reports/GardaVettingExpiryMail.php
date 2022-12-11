<?php

declare(strict_types=1);

namespace App\Mail\Reports;

use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GardaVettingExpiryMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly Collection $members)
    {
    }

    public function build(): GardaVettingExpiryMail
    {
        return $this->markdown('mail.reports.garda-vetting-expiry-mail', ['members' => $this->members])->subject('Garda Vetting Expiry Report');
    }
}
