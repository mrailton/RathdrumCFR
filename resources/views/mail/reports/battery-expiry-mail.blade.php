<x-mail::message>
@if($defibs->count() > 0)
The battery in the following defibs has either reached it's expiry date, or will expire within the next month, please ensure they are replaced ASAP.

@component('mail::table')
| Name | Location | Model | Battery Expiry Date |
| -----|:--------:|:-----:|-------------|
@foreach($defibs as $defib)
|{{ $defib->name }} | {{ $defib->location }} | {{ $defib->model }} | @if($defib->battery_expires_at) {{ $defib->battery_expires_at->format('d/m/Y') }} @else No Date @endif |
@endforeach
@endcomponent
@else
There are currently no defibs with an expiring or expired battery.
@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
