<x-mail::message>
@if($members->count() > 0)
The following members' CFR cert will expire within the next 2 months, please ensure they get re-certified

@component('mail::table')
| Name | Cert Expiry Date |
| ----- | -------- |
@foreach($members as $member)
|{{ $member->name }} | @if($member->cfr_certificate_expiry) {{ $member->cfr_certificate_expiry->format('d/m/Y') }} @else No Date @endif |
@endforeach
@endcomponent
@else
There are currently no members with expiring CFR certs.
@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
