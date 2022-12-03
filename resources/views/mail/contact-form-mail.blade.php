<x-mail::message>
# New Contact Form Submission

Hi there,

You have had a new submission on the contact form on the Rathdrum CFR website, details are below.

Name: {{ $name }}

Email: {{ $email }}

Phone: {{ $phone }}

Message: {{ $message }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
