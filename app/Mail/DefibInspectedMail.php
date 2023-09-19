<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Defib;
use App\Models\DefibInspection;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DefibInspectedMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(protected Defib $defib, protected DefibInspection $inspection)
    {
    }

    public function build(): DefibInspectedMail
    {
        return $this->markdown('mail.defib-inspected-mail', ['inspection' => $this->inspection, 'defib' => $this->defib]);
    }
}
