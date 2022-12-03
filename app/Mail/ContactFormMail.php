<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(protected string $name, protected string $email, protected string $phone, protected string $message)
    {
    }

    public function build(): ContactFormMail
    {
        return $this->markdown('mail.contact-form-mail', ['name' => $this->name, 'email' => $this->email, 'phone' => $this->phone, 'message' => $this->message])->subject('New Contact Form Submission');
    }
}
