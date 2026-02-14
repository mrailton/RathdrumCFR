@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $ampdsCode->code }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">AMPDS code details</p>
        </div>
        <div class="mt-4 flex gap-3 sm:mt-0">
            <x-admin.button variant="secondary" href="{{ route('admin.ampds-codes.index') }}">
                Back to List
            </x-admin.button>
            <x-admin.button href="{{ route('admin.ampds-codes.edit', $ampdsCode) }}">
                Edit Code
            </x-admin.button>
        </div>
    </div>

    <!-- Code Information -->
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="px-6 py-6 sm:p-8">
            <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white mb-5">Code Information</h3>
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Code</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $ampdsCode->code }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $ampdsCode->description }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Arrest Code</dt>
                    <dd class="mt-1 text-sm">
                        @if($ampdsCode->arrest_code)
                            <span class="inline-flex items-center rounded-full bg-green-50 dark:bg-green-900/20 px-2 py-1 text-xs font-medium text-green-700 dark:text-green-400">Yes</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-gray-50 dark:bg-gray-900/20 px-2 py-1 text-xs font-medium text-gray-600 dark:text-gray-400">No</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">FAR Code</dt>
                    <dd class="mt-1 text-sm">
                        @if($ampdsCode->far_code)
                            <span class="inline-flex items-center rounded-full bg-green-50 dark:bg-green-900/20 px-2 py-1 text-xs font-medium text-green-700 dark:text-green-400">Yes</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-gray-50 dark:bg-gray-900/20 px-2 py-1 text-xs font-medium text-gray-600 dark:text-gray-400">No</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection
