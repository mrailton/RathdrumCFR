<?php

declare(strict_types=1);

use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Mail;

test('the contact us form renders', function () {
    guest()
        ->get(route('contact'))
        ->assertSee('Rathdrum Community First Responders')
        ->assertSee('please fill in the form below and someone will get back to you as soon as possible.')
        ->assertSee('Name')
        ->assertSee('Contact Us');
});

test('the contact form can be submitted', function () {
    Mail::fake();

    $formData = [
        'name' => 'Test User',
        'email' => 'test@user.com',
        'phone' => '0831234567',
        'message' => 'This is a test'
    ];

    guest()
        ->post(route('contact'), $formData)
        ->assertRedirect(route('index'));

    Mail::assertSent(ContactFormMail::class);
});
