@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create Callout</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Add a new emergency callout</p>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <form method="POST" action="{{ route('admin.callouts.store') }}" 
            x-data="{
                mobilised: {{ old('mobilised', '0') }},
                attended: {{ old('attended', '0') }},
                ohcaAtScene: {{ old('ohca_at_scene', '0') }},
                roscAchieved: {{ old('rosc_achieved', '0') }}
            }"
            class="px-6 py-6 sm:p-8 space-y-6">
            @csrf

            <!-- Incident Details -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <x-admin.input 
                    label="CAD Number" 
                    name="incident_number" 
                    :value="old('incident_number')"
                    :required="true" 
                />

                <x-admin.input 
                    label="Incident Date" 
                    name="incident_date" 
                    type="datetime-local"
                    :value="old('incident_date', now()->format('Y-m-d\TH:i'))"
                    :required="true" 
                />

                <div class="sm:col-span-2">
                    <x-admin.select 
                        label="AMPDS Code" 
                        name="ampds_code" 
                        :value="old('ampds_code')"
                        :options="$ampdsCodes"
                        :required="true" 
                    />
                </div>

                <x-admin.input 
                    label="Age" 
                    name="age" 
                    type="number"
                    :value="old('age')"
                    :required="true" 
                />

                <x-admin.select 
                    label="Gender" 
                    name="gender" 
                    :value="old('gender')"
                    :options="['Unknown' => 'Unknown', 'Male' => 'Male', 'Female' => 'Female']"
                    :required="true" 
                />
            </div>

            <!-- Mobilisation -->
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">
                        Mobilised? <span class="text-red-600">*</span>
                    </label>
                    <div class="mt-2 space-y-2">
                        <label class="inline-flex items-center">
                            <input type="radio" name="mobilised" value="0" x-model="mobilised" class="text-red-600 focus:ring-red-600" required>
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">No</span>
                        </label>
                        <label class="inline-flex items-center ml-6">
                            <input type="radio" name="mobilised" value="1" x-model="mobilised" class="text-red-600 focus:ring-red-600" required>
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Yes</span>
                        </label>
                    </div>
                    @error('mobilised')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Medical Facility (only if not mobilised) -->
                <div x-show="mobilised == '0'" x-cloak>
                    <label class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">
                        Medical Facility?
                    </label>
                    <div class="mt-2 space-y-2">
                        <label class="inline-flex items-center">
                            <input type="radio" name="medical_facility" value="0" class="text-red-600 focus:ring-red-600">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">No</span>
                        </label>
                        <label class="inline-flex items-center ml-6">
                            <input type="radio" name="medical_facility" value="1" class="text-red-600 focus:ring-red-600">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Yes</span>
                        </label>
                    </div>
                    @error('medical_facility')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Members (only if mobilised) -->
                <div x-show="mobilised == '1'" x-cloak>
                    <label class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">
                        Members
                    </label>
                    <select 
                        name="members[]" 
                        multiple
                        size="10"
                        class="mt-1 block w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 dark:text-white dark:bg-gray-800 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6"
                    >
                        @foreach($members as $id => $name)
                            <option value="{{ $id }}" {{ in_array($id, old('members', [])) ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Hold Ctrl/Cmd to select multiple members</p>
                    @error('members')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Attended (only if mobilised) -->
                <div x-show="mobilised == '1'" x-cloak>
                    <label class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">
                        Attended?
                    </label>
                    <div class="mt-2 space-y-2">
                        <label class="inline-flex items-center">
                            <input type="radio" name="attended" value="0" x-model="attended" class="text-red-600 focus:ring-red-600">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">No</span>
                        </label>
                        <label class="inline-flex items-center ml-6">
                            <input type="radio" name="attended" value="1" x-model="attended" class="text-red-600 focus:ring-red-600">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Yes</span>
                        </label>
                    </div>
                    @error('attended')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- OHCA Section (only if attended) -->
            <div x-show="attended == '1'" x-cloak class="space-y-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Out of Hospital Cardiac Arrest</h3>
                
                <div>
                    <label class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">
                        OHCA at Scene?
                    </label>
                    <div class="mt-2 space-y-2">
                        <label class="inline-flex items-center">
                            <input type="radio" name="ohca_at_scene" value="0" x-model="ohcaAtScene" class="text-red-600 focus:ring-red-600">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">No</span>
                        </label>
                        <label class="inline-flex items-center ml-6">
                            <input type="radio" name="ohca_at_scene" value="1" x-model="ohcaAtScene" class="text-red-600 focus:ring-red-600">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Yes</span>
                        </label>
                    </div>
                    @error('ohca_at_scene')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- OHCA Details (only if OHCA at scene) -->
                <div x-show="ohcaAtScene == '1'" x-cloak class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">
                            Bystander CPR?
                        </label>
                        <div class="mt-2 space-y-2">
                            <label class="inline-flex items-center">
                                <input type="radio" name="bystander_cpr" value="0" class="text-red-600 focus:ring-red-600">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">No</span>
                            </label>
                            <label class="inline-flex items-center ml-6">
                                <input type="radio" name="bystander_cpr" value="1" class="text-red-600 focus:ring-red-600">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Yes</span>
                            </label>
                        </div>
                        @error('bystander_cpr')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <x-admin.select 
                        label="Source of AED" 
                        name="source_of_aed" 
                        :value="old('source_of_aed')"
                        :options="['CFR' => 'CFR', 'PAD' => 'PAD', 'NAS' => 'NAS', 'Fire' => 'Fire', 'Garda' => 'Garda', 'Other' => 'Other']"
                        placeholder="Select source"
                    />

                    <x-admin.input 
                        label="Number of Shocks Given" 
                        name="number_of_shocks_given" 
                        type="number"
                        :value="old('number_of_shocks_given')"
                    />

                    <div>
                        <label class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">
                            ROSC Achieved?
                        </label>
                        <div class="mt-2 space-y-2">
                            <label class="inline-flex items-center">
                                <input type="radio" name="rosc_achieved" value="0" x-model="roscAchieved" class="text-red-600 focus:ring-red-600">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">No</span>
                            </label>
                            <label class="inline-flex items-center ml-6">
                                <input type="radio" name="rosc_achieved" value="1" x-model="roscAchieved" class="text-red-600 focus:ring-red-600">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Yes</span>
                            </label>
                        </div>
                        @error('rosc_achieved')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Patient Transported (if OHCA=no OR ROSC=yes) -->
                <div x-show="ohcaAtScene == '0' || roscAchieved == '1'" x-cloak>
                    <label class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">
                        Patient Transported?
                    </label>
                    <div class="mt-2 space-y-2">
                        <label class="inline-flex items-center">
                            <input type="radio" name="patient_transported" value="0" class="text-red-600 focus:ring-red-600">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">No</span>
                        </label>
                        <label class="inline-flex items-center ml-6">
                            <input type="radio" name="patient_transported" value="1" class="text-red-600 focus:ring-red-600">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Yes</span>
                        </label>
                    </div>
                    @error('patient_transported')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Notes -->
            <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                <x-admin.textarea 
                    label="Notes" 
                    name="notes" 
                    :value="old('notes')"
                    rows="4"
                />
            </div>

            <!-- Actions -->
            <div class="flex gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <x-admin.button type="submit">
                    Create Callout
                </x-admin.button>
                <x-admin.button variant="secondary" href="{{ route('admin.callouts.index') }}">
                    Cancel
                </x-admin.button>
            </div>
        </form>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
