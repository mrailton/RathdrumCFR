<x-mail::message>
@if($defibs->count() > 0)
The following defibs have not been inspected in the last month, please ensure they are inspected ASAP.

@component('mail::table')
| Name | Location | Model | Last Inspected On | Pads Expiry Date |
| ----- | -------- | ----- | ------------- | ------------- |
@foreach($defibs as $defib)
|{{ $defib->name }} | {{ $defib->location }} | {{ $defib->model }} | @if($defib->last_inspected_at) {{ $defib->last_inspected_at->format('d/m/Y') }} @else No Date @endif | @if($defib->pads_expire_at) {{ $defib->pads_expire_at->format('d/m/Y') }} @else No Date @endif |
@endforeach
@endcomponent
@else
All defibs have been inspected with the last month, good job!
@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
