@extends('admin.layouts.app')

@section('title', $role->name)

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-6">
            <a href="{{ route('admin.roles.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">{{ $role->name }}</h1>
        </div>
        <div class="flex gap-2">
            @can('update', $role)
            <a href="{{ route('admin.roles.edit', $role) }}" class="inline-flex items-center justify-center rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">
                Edit
            </a>
            @endcan
            @can('delete', $role)
            @if($role->name !== 'Admin')
            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this role?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">
                    Delete
                </button>
            </form>
            @endif
            @endcan
        </div>
    </div>

    <!-- Permissions -->
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-6 py-6 sm:p-8">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Permissions ({{ $role->permissions->count() }})</h2>
            @if($role->permissions->count())
            <div class="flex flex-wrap gap-2">
                @foreach($role->permissions->sortBy('name') as $permission)
                <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-0.5 text-sm font-medium text-red-800">
                    {{ $permission->name }}
                </span>
                @endforeach
            </div>
            @else
            <p class="text-sm text-gray-500">No permissions assigned.</p>
            @endif
        </div>
    </div>

    <!-- Users with this role -->
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-6 py-6 sm:p-8">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Users with this role ({{ $role->users->count() }})</h2>
            @if($role->users->count())
            <ul class="divide-y divide-gray-200">
                @foreach($role->users as $user)
                <li class="py-3">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-gray-500">
                                <span class="text-sm font-medium leading-none text-white">{{ substr($user->name, 0, 1) }}</span>
                            </span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
            @else
            <p class="text-sm text-gray-500">No users have this role.</p>
            @endif
        </div>
    </div>
</div>
@endsection
