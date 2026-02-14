@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create Member</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Add a new team member or responder</p>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('admin.members.store') }}" class="space-y-6" x-data="{ currentTab: 'contact' }">
        @csrf

        <!-- Tabs -->
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                    <button type="button" @click="currentTab = 'contact'" :class="currentTab === 'contact' ? 'border-red-500 text-red-600 dark:text-red-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 hover:text-gray-700 dark:hover:text-gray-300'" class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">
                        Contact
                    </button>
                    <button type="button" @click="currentTab = 'address'" :class="currentTab === 'address' ? 'border-red-500 text-red-600 dark:text-red-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 hover:text-gray-700 dark:hover:text-gray-300'" class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">
                        Address
                    </button>
                    <button type="button" @click="currentTab = 'certs'" :class="currentTab === 'certs' ? 'border-red-500 text-red-600 dark:text-red-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 hover:text-gray-700 dark:hover:text-gray-300'" class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">
                        Certificates
                    </button>
                </nav>
            </div>

            <div class="px-6 py-6 sm:p-8">
                <!-- Contact Tab -->
                <div x-show="currentTab === 'contact'" class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <x-admin.input 
                            label="Name" 
                            name="name" 
                            :required="true"
                            class="sm:col-span-2"
                        />

                        <x-admin.select 
                            label="Status" 
                            name="status" 
                            :options="['inactive' => 'Inactive', 'active' => 'Active', 'new recruit' => 'New Recruit', 'buddying' => 'Buddying']"
                            value="inactive"
                            :required="true"
                        />

                        <x-admin.input 
                            label="Title" 
                            name="title" 
                            value="Responder"
                            :required="true"
                        />

                        <x-admin.input 
                            label="Phone" 
                            name="phone" 
                            type="tel"
                        />

                        <x-admin.input 
                            label="Email" 
                            name="email" 
                            type="email"
                        />
                    </div>
                </div>

                <!-- Address Tab -->
                <div x-show="currentTab === 'address'" class="space-y-6" x-cloak>
                    <x-admin.input 
                        label="Address Line 1" 
                        name="address_1"
                    />

                    <x-admin.input 
                        label="Address Line 2" 
                        name="address_2"
                    />

                    <x-admin.input 
                        label="Eircode" 
                        name="eircode"
                    />
                </div>

                <!-- Certificates Tab -->
                <div x-show="currentTab === 'certs'" class="space-y-6" x-cloak>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <x-admin.input 
                            label="CFR Certificate Number" 
                            name="cfr_certificate_number"
                        />

                        <x-admin.input 
                            label="CFR Certificate Expiry" 
                            name="cfr_certificate_expiry"
                            type="date"
                        />

                        <x-admin.input 
                            label="Volunteer Declaration Signed" 
                            name="volunteer_declaration"
                            type="date"
                        />

                        <x-admin.input 
                            label="Garda Vetting ID" 
                            name="garda_vetting_id"
                        />

                        <x-admin.input 
                            label="Garda Vetting Date" 
                            name="garda_vetting_date"
                            type="date"
                        />

                        <x-admin.input 
                            label="CISM Completed Date" 
                            name="cism_completed"
                            type="date"
                        />

                        <x-admin.input 
                            label="Children First Completed Date" 
                            name="child_first_completed"
                            type="date"
                        />

                        <x-admin.input 
                            label="PPE Community Completed Date" 
                            name="ppe_community_completed"
                            type="date"
                        />

                        <x-admin.input 
                            label="PPE Acute Completed Date" 
                            name="ppe_acute_completed"
                            type="date"
                        />

                        <x-admin.input 
                            label="Hand Hygiene Completed Date" 
                            name="hand_hygiene_completed"
                            type="date"
                        />

                        <x-admin.input 
                            label="HIQA Completed Date" 
                            name="hiqa_completed"
                            type="date"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-3">
            <x-admin.button variant="secondary" href="{{ route('admin.members.index') }}">
                Cancel
            </x-admin.button>
            <x-admin.button type="submit">
                Create Member
            </x-admin.button>
        </div>
    </form>
</div>
@endsection
