@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Member</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update member information</p>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('admin.members.update', $member) }}" class="space-y-6" x-data="{ currentTab: 'contact' }">
        @csrf
        @method('PUT')

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
                            :value="$member->name"
                            :required="true"
                            class="sm:col-span-2"
                        />

                        <x-admin.select 
                            label="Status" 
                            name="status" 
                            :options="['inactive' => 'Inactive', 'active' => 'Active', 'new recruit' => 'New Recruit', 'buddying' => 'Buddying']"
                            :value="$member->status"
                            :required="true"
                        />

                        <x-admin.input 
                            label="Title" 
                            name="title" 
                            :value="$member->title"
                            :required="true"
                        />

                        <x-admin.input 
                            label="Phone" 
                            name="phone" 
                            type="tel"
                            :value="$member->phone"
                        />

                        <x-admin.input 
                            label="Email" 
                            name="email" 
                            type="email"
                            :value="$member->email"
                        />
                    </div>
                </div>

                <!-- Address Tab -->
                <div x-show="currentTab === 'address'" class="space-y-6" x-cloak>
                    <x-admin.input 
                        label="Address Line 1" 
                        name="address_1"
                        :value="$member->address_1"
                    />

                    <x-admin.input 
                        label="Address Line 2" 
                        name="address_2"
                        :value="$member->address_2"
                    />

                    <x-admin.input 
                        label="Eircode" 
                        name="eircode"
                        :value="$member->eircode"
                    />
                </div>

                <!-- Certificates Tab -->
                <div x-show="currentTab === 'certs'" class="space-y-6" x-cloak>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <x-admin.input 
                            label="CFR Certificate Number" 
                            name="cfr_certificate_number"
                            :value="$member->cfr_certificate_number"
                        />

                        <x-admin.input 
                            label="CFR Certificate Expiry" 
                            name="cfr_certificate_expiry"
                            type="date"
                            :value="$member->cfr_certificate_expiry?->format('Y-m-d')"
                        />

                        <x-admin.input 
                            label="Volunteer Declaration Signed" 
                            name="volunteer_declaration"
                            type="date"
                            :value="$member->volunteer_declaration?->format('Y-m-d')"
                        />

                        <x-admin.input 
                            label="Garda Vetting ID" 
                            name="garda_vetting_id"
                            :value="$member->garda_vetting_id"
                        />

                        <x-admin.input 
                            label="Garda Vetting Date" 
                            name="garda_vetting_date"
                            type="date"
                            :value="$member->garda_vetting_date?->format('Y-m-d')"
                        />

                        <x-admin.input 
                            label="CISM Completed Date" 
                            name="cism_completed"
                            type="date"
                            :value="$member->cism_completed?->format('Y-m-d')"
                        />

                        <x-admin.input 
                            label="Children First Completed Date" 
                            name="child_first_completed"
                            type="date"
                            :value="$member->child_first_completed?->format('Y-m-d')"
                        />

                        <x-admin.input 
                            label="PPE Community Completed Date" 
                            name="ppe_community_completed"
                            type="date"
                            :value="$member->ppe_community_completed?->format('Y-m-d')"
                        />

                        <x-admin.input 
                            label="PPE Acute Completed Date" 
                            name="ppe_acute_completed"
                            type="date"
                            :value="$member->ppe_acute_completed?->format('Y-m-d')"
                        />

                        <x-admin.input 
                            label="Hand Hygiene Completed Date" 
                            name="hand_hygiene_completed"
                            type="date"
                            :value="$member->hand_hygiene_completed?->format('Y-m-d')"
                        />

                        <x-admin.input 
                            label="HIQA Completed Date" 
                            name="hiqa_completed"
                            type="date"
                            :value="$member->hiqa_completed?->format('Y-m-d')"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-between">
            <div>
                @if(!$member->trashed())
                    <button 
                        type="button"
                        onclick="if(confirm('Are you sure you want to delete this member?')) { document.getElementById('delete-form').submit(); }"
                        class="rounded-lg px-3 py-2 text-sm font-semibold text-red-600 hover:text-red-500"
                    >
                        Delete Member
                    </button>
                @endif
            </div>
            <div class="flex gap-3">
                <x-admin.button variant="secondary" href="{{ route('admin.members.show', $member) }}">
                    Cancel
                </x-admin.button>
                <x-admin.button type="submit">
                    Update Member
                </x-admin.button>
            </div>
        </div>
    </form>

    <!-- Delete Form -->
    @if(!$member->trashed())
        <form id="delete-form" method="POST" action="{{ route('admin.members.destroy', $member) }}" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endif
</div>
@endsection
