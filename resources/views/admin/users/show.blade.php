@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">User details</p>
        </div>
        <div class="mt-4 flex gap-3 sm:mt-0">
            <x-admin.button variant="secondary" href="{{ route('admin.users.index') }}">
                Back to List
            </x-admin.button>
            <x-admin.button href="{{ route('admin.users.edit', $user) }}">
                Edit User
            </x-admin.button>
        </div>
    </div>

    <!-- User Information -->
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="px-6 py-6 sm:p-8">
            <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white mb-5">User Information</h3>
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->email }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Assigned Roles</dt>
                    <dd class="mt-1 text-sm">
                        @if($user->roles->count() > 0)
                            <div class="flex gap-2 flex-wrap">
                                @foreach($user->roles as $role)
                                    <span class="inline-flex items-center rounded-full bg-red-50 dark:bg-red-900/20 px-3 py-1 text-sm font-medium text-red-700 dark:text-red-400">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-gray-900 dark:text-white">No roles assigned</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Account Details -->
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="px-6 py-6 sm:p-8">
            <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white mb-5">Account Details</h3>
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created At</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->created_at?->format('M j, Y H:i') ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->updated_at?->format('M j, Y H:i') ?: '-' }}</dd>
                </div>
                @if($user->last_login_at)
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Login</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->last_login_at->format('M j, Y H:i') }}</dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>
</div>
@endsection
