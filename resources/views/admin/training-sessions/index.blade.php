@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Training Sessions</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage training sessions and attendance</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <x-admin.button href="{{ route('admin.training-sessions.create') }}">
                Add Training Session
            </x-admin.button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="px-6 py-6 sm:p-8">
            <form method="GET" action="{{ route('admin.training-sessions.index') }}" class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                <!-- Date From -->
                <div>
                    <label for="from" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">From Date</label>
                    <input 
                        type="date" 
                        name="from" 
                        id="from"
                        value="{{ request('from', now()->startOfYear()->format('Y-m-d')) }}"
                        class="mt-1 block w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 dark:text-white dark:bg-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6"
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
                        class="mt-1 block w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 dark:text-white dark:bg-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6"
                    >
                </div>

                <div class="flex items-end gap-2">
                    <x-admin.button type="submit">Filter</x-admin.button>
                    @if(request()->hasAny(['from', 'to']))
                        <x-admin.button variant="secondary" href="{{ route('admin.training-sessions.index') }}">Clear</x-admin.button>
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
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white sm:pl-0">Date</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Topic</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Attendees</th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                @forelse($trainingSessions as $session)
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white sm:pl-0">
                                            {{ $session->date->format('d/m/Y') }}
                                        </td>
                                        <td class="px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $session->topic }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $session->members()->count() }}
                                        </td>
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('admin.training-sessions.show', $session) }}" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                                    View
                                                </a>
                                                <a href="{{ route('admin.training-sessions.edit', $session) }}" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                                    Edit
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-3 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                            No training sessions found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            @if($trainingSessions->hasPages())
                <div class="mt-5">
                    {{ $trainingSessions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
