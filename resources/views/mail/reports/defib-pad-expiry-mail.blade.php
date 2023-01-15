<x-mail::message>
@if($defibs->count() > 0)
The pads on the following defibs have either reached their expiry date, or will expire within the next month, please ensure they are replaced ASAP.

@component('mail::table')
| Name | Model | Pad Expiry Date |
| ----- | ----- | ------------- |
@foreach($defibs as $defib)
|{{ $defib->name }} | {{ $defib->model }} | @if($defib->pads_expire_at) {{ $defib->pads_expire_at->format('d/m/Y') }} @else No Date @endif |
@endforeach
@endcomponent
@else
There are currently no defibs with a expiring or expired pads.
@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
