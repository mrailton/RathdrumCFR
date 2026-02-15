@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Callouts</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage emergency callouts and responses</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <x-admin.button href="{{ route('admin.callouts.create') }}">
                Add Callout
            </x-admin.button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="px-6 py-6 sm:p-8">
            <form method="GET" action="{{ route('admin.callouts.index') }}" class="grid grid-cols-1 gap-6 sm:grid-cols-4">
                <!-- Search -->
                <div class="sm:col-span-2">
                    <label for="search" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">Search</label>
                    <input
                        type="text"
                        name="search"
                        id="search"
                        value="{{ request('search') }}"
                        placeholder="CAD number or AMPDS code..."
                        class="mt-1 block w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 dark:text-white dark:bg-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6"
                    >
                </div>

                <!-- Attended -->
                <div>
                    <label for="attended" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">Attended</label>
                    <select
                        name="attended"
                        id="attended"
                        class="mt-1 block w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 dark:text-white dark:bg-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6"
                    >
                        <option value="all">All</option>
                        <option value="1" {{ request('attended') === '1' ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ request('attended') === '0' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <!-- OHCA at Scene -->
                <div>
                    <label for="ohca_at_scene" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">OHCA at Scene</label>
                    <select
                        name="ohca_at_scene"
                        id="ohca_at_scene"
                        class="mt-1 block w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 dark:text-white dark:bg-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6"
                    >
                        <option value="all">All</option>
                        <option value="1" {{ request('ohca_at_scene') === '1' ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ request('ohca_at_scene') === '0' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label for="from" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">From Date</label>
                    <input
                        type="date"
                        name="from"
                        id="from"
                        value="{{ request('from', now()->startOfYear()->format('Y-m-d')) }}"
                        class="mt-1 block w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 dark:text-white dark:bg-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6"
                    >
                </div>

                <!-- Date To -->
                <div>
                    <label for="to" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">To Date</label>
                    <input
                        type="date"
                        name="to"
                        id="to"
                        value="{{ request('to', now()->endOfYear()->format('Y-m-d')) }}"
                        class="mt-1 block w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 dark:text-white dark:bg-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6"
                    >
                </div>

                <div class="sm:col-span-4 flex gap-2">
                    <x-admin.button type="submit">Filter</x-admin.button>
                    @if(request()->hasAny(['search', 'attended', 'ohca_at_scene', 'from', 'to']))
                        <x-admin.button variant="secondary" href="{{ route('admin.callouts.index') }}">Clear</x-admin.button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="px-6 py-6 sm:p-8">
            <div class="flow-root">
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
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Medical Facility</th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                @forelse($callouts as $callout)
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white sm:pl-0">
                                            {{ $callout->incident_number }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($callout->incident_date)->format('M j, Y H:i') }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $callout->ampds_code }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                                            @if($callout->mobilised)
                                                <span class="inline-flex items-center rounded-full bg-green-50 dark:bg-green-900/20 px-2 py-1 text-xs font-medium text-green-700 dark:text-green-400">Yes</span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-gray-50 dark:bg-gray-900/20 px-2 py-1 text-xs font-medium text-gray-600 dark:text-gray-400">No</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                                            @if($callout->attended)
                                                <span class="inline-flex items-center rounded-full bg-green-50 dark:bg-green-900/20 px-2 py-1 text-xs font-medium text-green-700 dark:text-green-400">Yes</span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-gray-50 dark:bg-gray-900/20 px-2 py-1 text-xs font-medium text-gray-600 dark:text-gray-400">No</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                                            @if($callout->medical_facility)
                                                <span class="inline-flex items-center rounded-full bg-green-50 dark:bg-green-900/20 px-2 py-1 text-xs font-medium text-green-700 dark:text-green-400">Yes</span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-gray-50 dark:bg-gray-900/20 px-2 py-1 text-xs font-medium text-gray-600 dark:text-gray-400">No</span>
                                            @endif
                                        </td>
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('admin.callouts.show', $callout) }}" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                                    View
                                                </a>
                                                <a href="{{ route('admin.callouts.edit', $callout) }}" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                                    Edit
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-3 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                            No callouts found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            @if($callouts->hasPages())
                <div class="mt-5">
                    {{ $callouts->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
