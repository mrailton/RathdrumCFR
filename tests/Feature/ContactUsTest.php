<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Mail;
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

        $formData = [
            'name' => 'Test User',
            'email' => 'test@user.com',
            'phone' => '0831234567',
            'message' => 'This is a test'
        ];

        $this->post(route('contact.store'), $formData)
            ->assertRedirect(route('index'));

        Mail::assertQueued(ContactFormMail::class);
    }
}
