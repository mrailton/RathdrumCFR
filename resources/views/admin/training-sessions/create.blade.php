@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create Training Session</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Add a new training session</p>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('admin.training-sessions.store') }}" class="space-y-6">
        @csrf

        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="px-6 py-6 sm:p-8">
                <div class="space-y-6">
                    <x-admin.input 
                        label="Date" 
                        name="date" 
                        type="date"
                        :value="now()->format('Y-m-d')"
                        :required="true"
                    />

                    <x-admin.input 
                        label="Topic" 
                        name="topic" 
                        :required="true"
                        placeholder="e.g., CPR Refresher, First Aid Basics"
                    />

                    <x-admin.textarea 
                        label="Notes" 
                        name="notes"
                        rows="4"
                        placeholder="Additional information about the session..."
                    />

                    <!-- Multi-select for attendees -->
                    <div class="space-y-1">
                        <label class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">
                            Attendees
                            <span class="text-red-600">*</span>
                        </label>
                        <div class="mt-2 max-h-60 overflow-y-auto rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 p-3">
                            @foreach($members as $member)
                                <div class="flex items-center py-2">
                                    <input 
                                        type="checkbox" 
                                        name="members[]" 
                                        value="{{ $member->id }}"
                                        id="member_{{ $member->id }}"
                                        {{ in_array($member->id, old('members', [])) ? 'checked' : '' }}
                                        class="h-4 w-4 rounded border-gray-300 dark:border-gray-700 text-red-600 focus:ring-red-600 dark:bg-gray-800"
                                    >
                                    <label for="member_{{ $member->id }}" class="ml-3 text-sm text-gray-700 dark:text-gray-300">
                                        {{ $member->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('members')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-3">
            <x-admin.button variant="secondary" href="{{ route('admin.training-sessions.index') }}">
                Cancel
            </x-admin.button>
            <x-admin.button type="submit">
                Create Training Session
            </x-admin.button>
        </div>
    </form>
</div>
@endsection
