<?php

declare(strict_types=1);

use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Mail;
use Lunaweb\RecaptchaV3\Facades\RecaptchaV3;

test('the contact us form renders', function () {
    $this->get(route('contact.create'))
        ->assertSee('Rathdrum Community First Responders')
        ->assertSee('please fill in the form below and someone will get back to you as soon as possible.')
        ->assertSee('Name')
        ->assertSee('Contact Us');
});

test('the contact form can be submitted', function () {
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
});
