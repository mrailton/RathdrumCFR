<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Mail;
use Lunaweb\RecaptchaV3\Facades\RecaptchaV3;
use Tests\TestCase;

class ContactUsTest extends TestCase
{
    /** @test */
    public function the_contact_us_form_renders(): void
    {
        $this->get(route('contact.create'))
            ->assertSee('Rathdrum Community First Responders')
            ->assertSee('please fill in the form below and someone will get back to you as soon as possible.')
            ->assertSee('Name')
            ->assertSee('Contact Us');
    }

    /** @test */
    public function the_contact_form_can_be_submitted(): void
    {
        Mail::fake();
        RecaptchaV3::shouldReceive('verify')
            ->once()
            ->andReturn(1.0);

        $formData = [
            'name' => 'Test User',
            'email' => 'test@user.com',
            'phone' => '0831234567',
            'message' => 'This is a test',
            'g-recaptcha-response' => '1'
        ];

        $this->post(route('contact.store'), $formData)
            ->assertRedirect(route('index'));

        Mail::assertQueued(ContactFormMail::class);
    }
}
