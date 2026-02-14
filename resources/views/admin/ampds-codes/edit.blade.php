@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit AMPDS Code</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update emergency dispatch protocol code</p>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('admin.ampds-codes.update', $ampdsCode) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="px-6 py-6 sm:p-8">
                <div class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <x-admin.input 
                            label="Code" 
                            name="code" 
                            :value="$ampdsCode->code"
                            :required="true"
                        />

                        <x-admin.input 
                            label="Description" 
                            name="description" 
                            :value="$ampdsCode->description"
                            :required="true"
                            class="sm:col-span-2"
                        />

                        <x-admin.checkbox 
                            label="Arrest Code" 
                            name="arrest_code"
                            :checked="$ampdsCode->arrest_code"
                            help="Indicates if this is a cardiac arrest code"
                        />

                        <x-admin.checkbox 
                            label="FAR Code" 
                            name="far_code"
                            :checked="$ampdsCode->far_code"
                            help="Indicates if this is a First Aid Response code"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-between">
            <div>
                <button 
                    type="button"
                    onclick="if(confirm('Are you sure you want to delete this AMPDS code?')) { document.getElementById('delete-form').submit(); }"
                    class="rounded-lg px-3 py-2 text-sm font-semibold text-red-600 hover:text-red-500"
                >
                    Delete AMPDS Code
                </button>
            </div>
            <div class="flex gap-3">
                <x-admin.button variant="secondary" href="{{ route('admin.ampds-codes.show', $ampdsCode) }}">
                    Cancel
                </x-admin.button>
                <x-admin.button type="submit">
                    Update AMPDS Code
                </x-admin.button>
            </div>
        </div>
    </form>

    <!-- Delete Form -->
    <form id="delete-form" method="POST" action="{{ route('admin.ampds-codes.destroy', $ampdsCode) }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</div>
@endsection
