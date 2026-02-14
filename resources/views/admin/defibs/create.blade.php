@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create Defibrillator</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Add a new defibrillator to the system</p>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('admin.defibs.store') }}" class="space-y-6" x-data="{ currentTab: 'defib' }">
        @csrf

        <!-- Tabs -->
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                    <button type="button" @click="currentTab = 'defib'" :class="currentTab === 'defib' ? 'border-red-500 text-red-600 dark:text-red-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 hover:text-gray-700 dark:hover:text-gray-300'" class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">
                        Defib
                    </button>
                    <button type="button" @click="currentTab = 'battery'" :class="currentTab === 'battery' ? 'border-red-500 text-red-600 dark:text-red-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 hover:text-gray-700 dark:hover:text-gray-300'" class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">
                        Battery
                    </button>
                    <button type="button" @click="currentTab = 'pads'" :class="currentTab === 'pads' ? 'border-red-500 text-red-600 dark:text-red-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 hover:text-gray-700 dark:hover:text-gray-300'" class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">
                        Pads
                    </button>
                </nav>
            </div>

            <div class="px-6 py-6 sm:p-8">
                <!-- Defib Tab -->
                <div x-show="currentTab === 'defib'" class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <x-admin.input 
                            label="Name" 
                            name="name" 
                            :required="true"
                            class="sm:col-span-2"
                        />

                        <x-admin.input 
                            label="Location" 
                            name="location" 
                            :required="true"
                            class="sm:col-span-2"
                        />

                        <x-admin.input 
                            label="Coordinates" 
                            name="coordinates"
                            placeholder="Latitude, Longitude"
                        />

                        <div class="flex items-end pb-2">
                            <x-admin.checkbox 
                                label="Display on Map" 
                                name="display_on_map"
                            />
                        </div>

                        <x-admin.input 
                            label="Model" 
                            name="model" 
                            :required="true"
                        />

                        <x-admin.input 
                            label="Serial Number" 
                            name="serial"
                        />

                        <x-admin.input 
                            label="Lot Number" 
                            name="defib_lot_number"
                        />

                        <x-admin.input 
                            label="Manufacture Date" 
                            name="defib_manufacture_date"
                            type="date"
                        />

                        <x-admin.input 
                            label="Owner" 
                            name="owner" 
                            value="RathdrumCFR"
                            :required="true"
                        />

                        <x-admin.input 
                            label="Last Serviced" 
                            name="last_serviced_at"
                            type="date"
                        />

                        <x-admin.input 
                            label="Last Inspected On" 
                            name="last_inspected_at"
                            type="date"
                        />

                        <x-admin.input 
                            label="Last Inspected By" 
                            name="last_inspected_by"
                        />
                    </div>
                </div>

                <!-- Battery Tab -->
                <div x-show="currentTab === 'battery'" class="space-y-6" x-cloak>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <x-admin.input 
                            label="Battery Lot Number" 
                            name="battery_lot_number"
                        />

                        <x-admin.input 
                            label="Battery Manufacture Date" 
                            name="battery_manufacture_date"
                            type="date"
                        />

                        <x-admin.input 
                            label="Battery Expiry" 
                            name="battery_expires_at"
                            type="date"
                        />
                    </div>
                </div>

                <!-- Pads Tab -->
                <div x-show="currentTab === 'pads'" class="space-y-6" x-cloak>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <x-admin.input 
                            label="Pads Lot Number" 
                            name="pads_lot_number"
                        />

                        <x-admin.input 
                            label="Pads Manufacture Date" 
                            name="pads_manufacture_date"
                            type="date"
                        />

                        <x-admin.input 
                            label="Pads Expiry" 
                            name="pads_expire_at"
                            type="date"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-3">
            <x-admin.button variant="secondary" href="{{ route('admin.defibs.index') }}">
                Cancel
            </x-admin.button>
            <x-admin.button type="submit">
                Create Defib
            </x-admin.button>
        </div>
    </form>
</div>
@endsection
