<x-mail::message>
Hi {{ $invite->name }},

You have been invited to create an account at {{ config('app.name') }}. Click on the button below in order to create your new account

<x-mail::button :url="route('register.create', ['invite' => $invite->token])">
Create Account
</x-mail::button>

Please note that this invitation is only valid for the next 48 hours

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
