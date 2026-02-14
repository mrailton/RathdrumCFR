@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create User</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Add a new system user</p>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
        @csrf

        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="px-6 py-6 sm:p-8">
                <div class="space-y-6">
                    <x-admin.input 
                        label="Name" 
                        name="name" 
                        :required="true"
                    />

                    <x-admin.input 
                        label="Email" 
                        name="email" 
                        type="email"
                        :required="true"
                    />

                    <div>
                        <x-admin.input 
                            label="Password" 
                            name="password" 
                            type="password"
                            :required="true"
                        />
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Minimum 8 characters</p>
                    </div>

                    <!-- Roles -->
                    <div x-data="{ selectedRoles: [] }">
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
                                        {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}
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
            <x-admin.button variant="secondary" href="{{ route('admin.users.index') }}">
                Cancel
            </x-admin.button>
            <x-admin.button type="submit">
                Create User
            </x-admin.button>
        </div>
    </form>
</div>
@endsection
