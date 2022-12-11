<x-mail::message>
@if($members->count() > 0)
The following members' Garda Vetting will expire within the next 2 months, please ensure they renew their vetting ASAP

@component('mail::table')
| Name | Garda Vetting Expiry Date |
| ----- | -------- |
@foreach($members as $member)
|{{ $member->name }} | @if($member->garda_vetting_date) {{ $member->garda_vetting_date->addYears(3)->format('d/m/Y') }} @else No Date @endif |
@endforeach
@endcomponent
@else
There are currently no members with expiring Garda Vetting.
@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
