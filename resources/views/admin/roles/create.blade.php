@extends('admin.layouts.app')

@section('title', 'Create Role')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-6">
        <a href="{{ route('admin.roles.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Create Role</h1>
    </div>

    <form action="{{ route('admin.roles.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-6 py-6 sm:p-8">
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm @error('name') border-red-300 @enderror">
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Permissions</label>
                        <div class="space-y-6">
                            @foreach($permissions as $group => $groupPermissions)
                            <div class="border rounded-lg p-4">
                                <h3 class="text-sm font-semibold text-gray-900 mb-3 capitalize">{{ str_replace('::', ' ', $group) }}</h3>
                                <div class="grid grid-cols-2 gap-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
                                    @foreach($groupPermissions as $permission)
                                    <label class="flex items-center gap-2 text-sm">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                                        <span class="text-gray-700">{{ str_replace('_' . $group, '', str_replace('_', ' ', $permission->name)) }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                <a href="{{ route('admin.roles.index') }}" class="mr-3 inline-flex justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">Cancel</a>
                <button type="submit" class="inline-flex justify-center rounded-lg border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700">Create Role</button>
            </div>
        </div>
    </form>
</div>
@endsection
