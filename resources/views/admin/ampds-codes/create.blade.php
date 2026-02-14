@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create AMPDS Code</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Add a new emergency dispatch protocol code</p>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('admin.ampds-codes.store') }}" class="space-y-6">
        @csrf

        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="px-6 py-6 sm:p-8">
                <div class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <x-admin.input 
                            label="Code" 
                            name="code" 
                            :required="true"
                            placeholder="e.g., 1-A-1"
                        />

                        <x-admin.input 
                            label="Description" 
                            name="description" 
                            :required="true"
                            placeholder="e.g., Abdominal Pain"
                            class="sm:col-span-2"
                        />

                        <x-admin.checkbox 
                            label="Arrest Code" 
                            name="arrest_code"
                            help="Indicates if this is a cardiac arrest code"
                        />

                        <x-admin.checkbox 
                            label="FAR Code" 
                            name="far_code"
                            help="Indicates if this is a First Aid Response code"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-3">
            <x-admin.button variant="secondary" href="{{ route('admin.ampds-codes.index') }}">
                Cancel
            </x-admin.button>
            <x-admin.button type="submit">
                Create AMPDS Code
            </x-admin.button>
        </div>
    </form>
</div>
@endsection
