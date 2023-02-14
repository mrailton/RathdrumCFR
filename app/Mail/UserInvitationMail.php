<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Invite;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserInvitationMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(protected Invite $invite)
    {
    }

    public function build(): UserInvitationMail
    {
        return $this->markdown('mail.user-invitation-mail', ['invite' => $this->invite]);
    }
}
