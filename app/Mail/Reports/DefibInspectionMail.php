<?php

declare(strict_types=1);

namespace App\Mail\Reports;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DefibInspectionMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(protected Collection $defibs)
    {
    }

    public function build(): DefibInspectionMail
    {
        return $this->markdown('mail.reports.defib-inspection-mail', ['defibs' => $this->defibs])->subject('Overdue Defib Inspection Report');
    }
}
