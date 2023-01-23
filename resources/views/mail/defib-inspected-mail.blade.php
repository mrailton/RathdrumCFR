<x-mail::message>
Hi there,\
The following defib has just been inspected by {{ $inspection->member->name }}

Name: {{ $defib->name }}\
Inspected On: {{ $inspection->inspected_at->format('l jS F Y') }}\
Pads Expire On: {{ $inspection->pads_expire_at->format('l jS F Y') }}\
Battery Expires On: {{ $inspection->battery_expires_at->format('l jS F Y') }}\
Notes: {{ $inspection->notes }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
