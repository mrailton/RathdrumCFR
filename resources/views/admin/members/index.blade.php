@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Members</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage your team members and responders</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <x-admin.button href="{{ route('admin.members.create') }}">
                Add Member
            </x-admin.button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="px-6 py-6 sm:p-8">
            <form method="GET" action="{{ route('admin.members.index') }}" class="grid grid-cols-1 gap-6 sm:grid-cols-4">
                <!-- Search -->
                <div class="sm:col-span-2">
                    <label for="search" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">Search</label>
                    <input 
                        type="text" 
                        name="search" 
                        id="search"
                        value="{{ request('search') }}"
                        placeholder="Name, email, or phone..."
                        class="mt-1 block w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 dark:text-white dark:bg-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6"
                    >
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">Status</label>
                    <select 
                        name="status" 
                        id="status"
                        class="mt-1 block w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 dark:text-white dark:bg-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6"
                    >
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="new recruit" {{ request('status') === 'new recruit' ? 'selected' : '' }}>New Recruit</option>
                        <option value="buddying" {{ request('status') === 'buddying' ? 'selected' : '' }}>Buddying</option>
                    </select>
                </div>

                <!-- Trashed -->
                <div>
                    <label for="trashed" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">Deleted</label>
                    <select 
                        name="trashed" 
                        id="trashed"
                        class="mt-1 block w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 dark:text-white dark:bg-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6"
                    >
                        <option value="">Without Deleted</option>
                        <option value="with" {{ request('trashed') === 'with' ? 'selected' : '' }}>With Deleted</option>
                        <option value="only" {{ request('trashed') === 'only' ? 'selected' : '' }}>Only Deleted</option>
                    </select>
                </div>

                <div class="sm:col-span-4 flex gap-2">
                    <x-admin.button type="submit">Filter</x-admin.button>
                    @if(request()->hasAny(['search', 'status', 'trashed']))
                        <x-admin.button variant="secondary" href="{{ route('admin.members.index') }}">Clear</x-admin.button>
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
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white sm:pl-0">Name</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Phone</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Email</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Status</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">CFR Cert Expiry</th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                @forelse($members as $member)
                                    <tr class="{{ $member->trashed() ? 'bg-gray-50 dark:bg-gray-900/50' : '' }}">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium sm:pl-0">
                                            <div class="flex items-center gap-2">
                                                <span class="text-gray-900 dark:text-white">{{ $member->name }}</span>
                                                @if($member->trashed())
                                                    <span class="inline-flex items-center rounded-full bg-red-50 dark:bg-red-900/20 px-2 py-1 text-xs font-medium text-red-700 dark:text-red-400">Deleted</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $member->phone ?: '-' }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $member->email ?: '-' }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                                            @if($member->status === 'active')
                                                <span class="inline-flex items-center rounded-full bg-green-50 dark:bg-green-900/20 px-2 py-1 text-xs font-medium text-green-700 dark:text-green-400">{{ ucwords($member->status) }}</span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-gray-50 dark:bg-gray-900/20 px-2 py-1 text-xs font-medium text-gray-600 dark:text-gray-400">{{ ucwords($member->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $member->cfr_certificate_expiry?->format('M j, Y') ?: '-' }}
                                        </td>
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                            <div class="flex justify-end gap-2">
                                                @if($member->trashed())
                                                    <form method="POST" action="{{ route('admin.members.restore', $member) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300">
                                                            Restore
                                                        </button>
                                                    </form>
                                                @else
                                                    <a href="{{ route('admin.members.show', $member) }}" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                                        View
                                                    </a>
                                                    <a href="{{ route('admin.members.edit', $member) }}" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                                        Edit
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-3 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                            No members found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            @if($members->hasPages())
                <div class="mt-5">
                    {{ $members->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
