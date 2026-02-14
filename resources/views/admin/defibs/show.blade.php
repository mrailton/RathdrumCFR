@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $defib->name }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $defib->location }}</p>
        </div>
        <div class="mt-4 sm:mt-0 flex gap-3">
            <x-admin.button variant="secondary" href="{{ route('admin.defibs.index') }}">
                Back to List
            </x-admin.button>
            <x-admin.button href="{{ route('admin.defibs.edit', $defib) }}">
                Edit
            </x-admin.button>
        </div>
    </div>

    <!-- Defib Details -->
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="px-6 py-6 sm:p-8">
            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4">Defib Information</h3>
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $defib->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Location</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $defib->location }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Coordinates</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $defib->coordinates ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Display on Map</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        @if($defib->display_on_map)
                            <span class="inline-flex items-center rounded-full bg-green-50 dark:bg-green-900/20 px-2 py-1 text-xs font-medium text-green-700 dark:text-green-400">Yes</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-gray-50 dark:bg-gray-900/20 px-2 py-1 text-xs font-medium text-gray-600 dark:text-gray-400">No</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Model</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $defib->model }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Serial Number</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $defib->serial ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Lot Number</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $defib->defib_lot_number ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Manufacture Date</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $defib->defib_manufacture_date?->format('M j, Y') ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Owner</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $defib->owner }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Serviced</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $defib->last_serviced_at?->format('M j, Y') ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Inspected On</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $defib->last_inspected_at?->format('M j, Y') ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Inspected By</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $defib->last_inspected_by ?: '-' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Battery Details -->
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="px-6 py-6 sm:p-8">
            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4">Battery Information</h3>
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Battery Lot Number</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $defib->battery_lot_number ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Battery Manufacture Date</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $defib->battery_manufacture_date?->format('M j, Y') ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Battery Expiry</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $defib->battery_expires_at?->format('M j, Y') ?: '-' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Pads Details -->
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="px-6 py-6 sm:p-8">
            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4">Pads Information</h3>
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Pads Lot Number</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $defib->pads_lot_number ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Pads Manufacture Date</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $defib->pads_manufacture_date?->format('M j, Y') ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Pads Expiry</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $defib->pads_expire_at?->format('M j, Y') ?: '-' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Inspections -->
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="px-6 py-6 sm:p-8">
            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4">Inspections</h3>
            <div class="flow-root">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white sm:pl-0">Date</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Inspected By</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Status</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                @forelse($defib->inspections as $inspection)
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-900 dark:text-white sm:pl-0">
                                            {{ $inspection->inspected_at?->format('M j, Y') }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $inspection->inspected_by ?: '-' }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $inspection->status ?: '-' }}
                                        </td>
                                        <td class="px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $inspection->notes ?: '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-3 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                            No inspections recorded.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes -->
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="px-6 py-6 sm:p-8">
            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4">Notes</h3>
            <div class="flow-root">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white sm:pl-0">Date</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Note</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                @forelse($defib->notes as $note)
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-900 dark:text-white sm:pl-0">
                                            {{ $note->created_at?->format('M j, Y') }}
                                        </td>
                                        <td class="px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $note->note }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-3 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                            No notes recorded.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg border-2 border-red-200 dark:border-red-900">
        <div class="px-6 py-6 sm:p-8">
            <h3 class="text-lg font-medium leading-6 text-red-900 dark:text-red-400 mb-4">Danger Zone</h3>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Delete this defibrillator from the system</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">This action can be undone by restoring from the deleted items list</p>
                </div>
                <form method="POST" action="{{ route('admin.defibs.destroy', $defib) }}" onsubmit="return confirm('Are you sure you want to delete this defibrillator?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center rounded-lg bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">
                        Delete Defib
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
