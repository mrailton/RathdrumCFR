@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $member->name }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Member details and history</p>
        </div>
        <div class="mt-4 flex gap-3 sm:mt-0">
            <x-admin.button variant="secondary" href="{{ route('admin.members.index') }}">
                Back to List
            </x-admin.button>
            @if(!$member->trashed())
                <x-admin.button href="{{ route('admin.members.edit', $member) }}">
                    Edit Member
                </x-admin.button>
            @endif
        </div>
    </div>

    @if($member->trashed())
        <div class="rounded-lg bg-red-50 dark:bg-red-900/20 p-4">
            <div class="flex">
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-400">This member has been deleted</h3>
                </div>
            </div>
        </div>
    @endif

    <!-- Contact Information -->
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="px-6 py-6 sm:p-8">
            <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white mb-5">Contact Information</h3>
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $member->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                    <dd class="mt-1 text-sm">
                        @if($member->status === 'active')
                            <span class="inline-flex items-center rounded-full bg-green-50 dark:bg-green-900/20 px-2 py-1 text-xs font-medium text-green-700 dark:text-green-400">{{ ucwords($member->status) }}</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-gray-50 dark:bg-gray-900/20 px-2 py-1 text-xs font-medium text-gray-600 dark:text-gray-400">{{ ucwords($member->status) }}</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Title</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $member->title }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $member->phone ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $member->email ?: '-' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Address Information -->
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="px-6 py-6 sm:p-8">
            <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white mb-5">Address</h3>
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Address Line 1</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $member->address_1 ?: '-' }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Address Line 2</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $member->address_2 ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Eircode</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $member->eircode ?: '-' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Certificates & Training -->
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="px-6 py-6 sm:p-8">
            <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white mb-5">Certificates & Training</h3>
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">CFR Certificate Number</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $member->cfr_certificate_number ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">CFR Certificate Expiry</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $member->cfr_certificate_expiry?->format('M j, Y') ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Volunteer Declaration Signed</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $member->volunteer_declaration?->format('M j, Y') ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Garda Vetting ID</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $member->garda_vetting_id ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Garda Vetting Date</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $member->garda_vetting_date?->format('M j, Y') ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">CISM Completed</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $member->cism_completed?->format('M j, Y') ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Children First Completed</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $member->child_first_completed?->format('M j, Y') ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">PPE Community Completed</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $member->ppe_community_completed?->format('M j, Y') ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">PPE Acute Completed</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $member->ppe_acute_completed?->format('M j, Y') ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Hand Hygiene Completed</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $member->hand_hygiene_completed?->format('M j, Y') ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">HIQA Completed</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $member->hiqa_completed?->format('M j, Y') ?: '-' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Training Sessions -->
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="px-6 py-6 sm:p-8">
            <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white mb-5">Training Sessions Attended</h3>
            @if($member->trainingSessions->count() > 0)
                <div class="flow-root">
                    <ul role="list" class="-my-5 divide-y divide-gray-200 dark:divide-gray-800">
                        @foreach($member->trainingSessions->sortByDesc('date') as $session)
                            <li class="py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-medium text-gray-900 dark:text-white">{{ $session->topic }}</p>
                                        <p class="truncate text-sm text-gray-500 dark:text-gray-400">{{ $session->date->format('M j, Y') }}</p>
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.training-sessions.show', $session) }}" class="inline-flex items-center rounded-full bg-white dark:bg-gray-900 px-2.5 py-1 text-xs font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                                            View
                                        </a>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400">No training sessions recorded.</p>
            @endif
        </div>
    </div>

    <!-- Callouts -->
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="px-6 py-6 sm:p-8">
            <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white mb-5">Callouts Attended</h3>
            @if($member->callouts->count() > 0)
                <div class="flow-root">
                    <ul role="list" class="-my-5 divide-y divide-gray-200 dark:divide-gray-800">
                        @foreach($member->callouts->sortByDesc('incident_date') as $callout)
                            <li class="py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-medium text-gray-900 dark:text-white">{{ $callout->incident_number }}</p>
                                        <p class="truncate text-sm text-gray-500 dark:text-gray-400">
                                            {{ $callout->incident_date->format('M j, Y H:i') }} - {{ $callout->ampds_code }}
                                        </p>
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.callouts.show', $callout) }}" class="inline-flex items-center rounded-full bg-white dark:bg-gray-900 px-2.5 py-1 text-xs font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                                            View
                                        </a>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400">No callouts recorded.</p>
            @endif
        </div>
    </div>
</div>
@endsection
