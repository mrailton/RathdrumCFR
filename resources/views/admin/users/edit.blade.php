@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit User</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update user information</p>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="px-6 py-6 sm:p-8">
                <div class="space-y-6">
                    <x-admin.input 
                        label="Name" 
                        name="name" 
                        :value="old('name', $user->name)"
                        :required="true"
                    />

                    <x-admin.input 
                        label="Email" 
                        name="email" 
                        type="email"
                        :value="old('email', $user->email)"
                        :required="true"
                    />

                    <div>
                        <label for="password" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">
                            Password
                        </label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password"
                            disabled
                            class="mt-1 block w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 dark:text-white dark:bg-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6 disabled:cursor-not-allowed disabled:bg-gray-50 dark:disabled:bg-gray-800 disabled:text-gray-500"
                            placeholder="Cannot be updated"
                        >
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Password cannot be changed here</p>
                    </div>

                    <!-- Roles -->
                    <div x-data="{ selectedRoles: {{ json_encode($user->roles->pluck('id')->toArray()) }} }">
                        <label class="block text-sm font-medium leading-6 text-gray-900 dark:text-white mb-3">
                            Roles
                        </label>
                        <div class="space-y-2">
                            @foreach($roles as $role)
                                <div class="flex items-center">
                                    <input 
                                        type="checkbox" 
                                        name="roles[]" 
                                        id="role_{{ $role->id }}"
                                        value="{{ $role->id }}"
                                        {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'checked' : '' }}
                                        class="h-4 w-4 rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-red-600 focus:ring-red-600"
                                    >
                                    <label for="role_{{ $role->id }}" class="ml-2 block text-sm text-gray-900 dark:text-white">
                                        {{ $role->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('roles')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-3">
            <x-admin.button variant="secondary" href="{{ route('admin.users.show', $user) }}">
                Cancel
            </x-admin.button>
            <x-admin.button type="submit">
                Update User
            </x-admin.button>
        </div>
    </form>

    <!-- Delete Section -->
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="px-6 py-6 sm:p-8">
            <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">Delete User</h3>
            <div class="mt-2 max-w-xl text-sm text-gray-500 dark:text-gray-400">
                <p>Once you delete this user, all of their data will be permanently removed.</p>
            </div>
            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="mt-5" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                    Delete User
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
