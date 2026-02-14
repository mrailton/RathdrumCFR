@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Overview of your CFR operations</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <!-- Members Stats -->
        <div class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-6 py-6 shadow sm:p-8">
            <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Total Members</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ $memberStats['total'] }}</dd>
            <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Active:</span>
                    <span class="ml-1 font-medium text-green-600 dark:text-green-400">{{ $memberStats['active'] }}</span>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Inactive:</span>
                    <span class="ml-1 font-medium text-gray-600 dark:text-gray-400">{{ $memberStats['inactive'] }}</span>
                </div>
            </div>
        </div>

        <!-- Callouts Stats -->
        <div class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-6 py-6 shadow sm:p-8">
            <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Callouts ({{ now()->year }})</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ $calloutStats['total'] }}</dd>
            <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Attended:</span>
                    <span class="ml-1 font-medium text-green-600 dark:text-green-400">{{ $calloutStats['attended'] }}</span>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400">OHCA:</span>
                    <span class="ml-1 font-medium text-red-600 dark:text-red-400">{{ $calloutStats['ohca'] }}</span>
                </div>
            </div>
        </div>

        <!-- Defibs Stats -->
        <div class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-6 py-6 shadow sm:p-8">
            <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Defibrillators</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ $defibStats['total'] }}</dd>
            <div class="mt-3 text-xs">
                <span class="text-gray-500 dark:text-gray-400">Requiring Attention:</span>
                <span class="ml-1 font-medium {{ $defibStats['needs_attention'] > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                    {{ $defibStats['needs_attention'] }}
                </span>
            </div>
        </div>
    </div>

    <!-- Recent Callouts -->
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="px-6 py-6 sm:p-8">
            <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">Recent Callouts</h3>
            <div class="mt-5 flow-root">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white sm:pl-0">CAD Number</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Date/Time</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">AMPDS Code</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Mobilised</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Attended</th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                @forelse($recentCallouts as $callout)
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white sm:pl-0">
                                            {{ $callout->incident_number }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $callout->incident_date->format('M j, Y H:i') }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $callout->ampds_code }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            @if($callout->mobilised)
                                                <span class="inline-flex items-center rounded-full bg-green-50 dark:bg-green-900/20 px-2 py-1 text-xs font-medium text-green-700 dark:text-green-400">Yes</span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-gray-50 dark:bg-gray-900/20 px-2 py-1 text-xs font-medium text-gray-600 dark:text-gray-400">No</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            @if($callout->attended)
                                                <span class="inline-flex items-center rounded-full bg-green-50 dark:bg-green-900/20 px-2 py-1 text-xs font-medium text-green-700 dark:text-green-400">Yes</span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-gray-50 dark:bg-gray-900/20 px-2 py-1 text-xs font-medium text-gray-600 dark:text-gray-400">No</span>
                                            @endif
                                        </td>
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                            <a href="{{ route('admin.callouts.show', $callout) }}" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-3 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                            No recent callouts found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @if($recentCallouts->isNotEmpty())
                <div class="mt-5">
                    <x-admin.button variant="secondary" href="{{ route('admin.callouts.index') }}">
                        View All Callouts
                    </x-admin.button>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
