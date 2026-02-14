@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Callout Details</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $callout->incident_number }}</p>
        </div>
        <div class="mt-4 sm:mt-0 flex gap-2">
            <x-admin.button href="{{ route('admin.callouts.edit', $callout) }}">
                Edit
            </x-admin.button>
            <form method="POST" action="{{ route('admin.callouts.destroy', $callout) }}" onsubmit="return confirm('Are you sure you want to delete this callout?');">
                @csrf
                @method('DELETE')
                <x-admin.button type="submit" variant="secondary">
                    Delete
                </x-admin.button>
            </form>
        </div>
    </div>

    <!-- Incident Details -->
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="px-6 py-6 sm:p-8">
            <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white mb-4">Incident Information</h3>
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">CAD Number</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $callout->incident_number }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Incident Date</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ \Carbon\Carbon::parse($callout->incident_date)->format('F j, Y \a\t g:i A') }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">AMPDS Code</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $callout->ampds_code }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Age</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $callout->age }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Gender</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $callout->gender }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Mobilised</dt>
                    <dd class="mt-1">
                        @if($callout->mobilised)
                            <span class="inline-flex items-center rounded-full bg-green-50 dark:bg-green-900/20 px-2 py-1 text-xs font-medium text-green-700 dark:text-green-400">Yes</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-gray-50 dark:bg-gray-900/20 px-2 py-1 text-xs font-medium text-gray-600 dark:text-gray-400">No</span>
                        @endif
                    </dd>
                </div>

                @if(!$callout->mobilised)
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Medical Facility</dt>
                        <dd class="mt-1">
                            @if($callout->medical_facility)
                                <span class="inline-flex items-center rounded-full bg-green-50 dark:bg-green-900/20 px-2 py-1 text-xs font-medium text-green-700 dark:text-green-400">Yes</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-gray-50 dark:bg-gray-900/20 px-2 py-1 text-xs font-medium text-gray-600 dark:text-gray-400">No</span>
                            @endif
                        </dd>
                    </div>
                @endif

                @if($callout->mobilised)
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Members</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            @if($callout->members->count() > 0)
                                <div class="flex flex-wrap gap-2">
                                    @foreach($callout->members as $member)
                                        <a href="{{ route('admin.members.show', $member) }}" class="inline-flex items-center rounded-full bg-red-50 dark:bg-red-900/20 px-3 py-1 text-xs font-medium text-red-700 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/30">
                                            {{ $member->name }}
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-500 dark:text-gray-400">No members assigned</span>
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Attended</dt>
                        <dd class="mt-1">
                            @if($callout->attended)
                                <span class="inline-flex items-center rounded-full bg-green-50 dark:bg-green-900/20 px-2 py-1 text-xs font-medium text-green-700 dark:text-green-400">Yes</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-gray-50 dark:bg-gray-900/20 px-2 py-1 text-xs font-medium text-gray-600 dark:text-gray-400">No</span>
                            @endif
                        </dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- OHCA Details -->
    @if($callout->attended)
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="px-6 py-6 sm:p-8">
                <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white mb-4">Out of Hospital Cardiac Arrest</h3>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">OHCA at Scene</dt>
                        <dd class="mt-1">
                            @if($callout->ohca_at_scene)
                                <span class="inline-flex items-center rounded-full bg-red-50 dark:bg-red-900/20 px-2 py-1 text-xs font-medium text-red-700 dark:text-red-400">Yes</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-gray-50 dark:bg-gray-900/20 px-2 py-1 text-xs font-medium text-gray-600 dark:text-gray-400">No</span>
                            @endif
                        </dd>
                    </div>

                    @if($callout->ohca_at_scene)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Bystander CPR</dt>
                            <dd class="mt-1">
                                @if($callout->bystander_cpr)
                                    <span class="inline-flex items-center rounded-full bg-green-50 dark:bg-green-900/20 px-2 py-1 text-xs font-medium text-green-700 dark:text-green-400">Yes</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-gray-50 dark:bg-gray-900/20 px-2 py-1 text-xs font-medium text-gray-600 dark:text-gray-400">No</span>
                                @endif
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Source of AED</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $callout->source_of_aed ?? '-' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Number of Shocks Given</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $callout->number_of_shocks_given ?? '-' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ROSC Achieved</dt>
                            <dd class="mt-1">
                                @if($callout->rosc_achieved)
                                    <span class="inline-flex items-center rounded-full bg-green-50 dark:bg-green-900/20 px-2 py-1 text-xs font-medium text-green-700 dark:text-green-400">Yes</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-gray-50 dark:bg-gray-900/20 px-2 py-1 text-xs font-medium text-gray-600 dark:text-gray-400">No</span>
                                @endif
                            </dd>
                        </div>
                    @endif

                    @if(!$callout->ohca_at_scene || $callout->rosc_achieved)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Patient Transported</dt>
                            <dd class="mt-1">
                                @if($callout->patient_transported)
                                    <span class="inline-flex items-center rounded-full bg-green-50 dark:bg-green-900/20 px-2 py-1 text-xs font-medium text-green-700 dark:text-green-400">Yes</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-gray-50 dark:bg-gray-900/20 px-2 py-1 text-xs font-medium text-gray-600 dark:text-gray-400">No</span>
                                @endif
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>
    @endif

    <!-- Notes -->
    @if($callout->notes)
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="px-6 py-6 sm:p-8">
                <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white mb-4">Notes</h3>
                <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $callout->notes }}</p>
            </div>
        </div>
    @endif

    <!-- Back Button -->
    <div>
        <x-admin.button variant="secondary" href="{{ route('admin.callouts.index') }}">
            Back to Callouts
        </x-admin.button>
    </div>
</div>
@endsection
