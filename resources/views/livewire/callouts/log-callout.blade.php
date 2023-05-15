<div>
    <button wire:click.prevent="openCreateModal" type="button" class="inline-flex items-center justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:w-auto">
        Log Callout
    </button>

    <div class="@if (!$showCreateModal) hidden @endif fixed inset-0 z-50 overflow-y-auto" role="dialog">
        <div class="flex items-end justify-center min-h-screen px-4 text-center md:items-center sm:block sm:p-0">
            <div
                class="inline-block w-full max-w-xl p-8 my-20 overflow-hidden text-left transition-all transform bg-white rounded-lg shadow-xl 2xl:max-w-2xl">
                <div class="flex items-center justify-between space-x-4">
                    <h1 class="text-xl font-medium text-gray-800 ">Log Callout</h1>

                    <button wire:click="closeCreateModal"
                            class="text-gray-600 focus:outline-none hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveCallout" class="w-full">
                    <div class="flex flex-col items-start p-4">
                        <div class="mb-2 w-full">
                            <label class="block text-sm font-medium text-gray-700" for="callout.incident_number">
                                Incident Number
                            </label>

                            <input
                                type="text"
                                wire:model="callout.incident_number"
                                id="callout.incident_number"
                                class="py-2 pr-4 pl-2 mt-2 w-full text-sm rounded-lg border border-gray-400 sm:text-base focus:outline-none focus:border-blue-400"
                            />
                            @error('callout.incident_number')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-2 w-full">
                            <label class="block text-sm font-medium text-gray-700" for="callout.incident_date">
                                Incident Date
                            </label>

                            <input
                                type="datetime-local"
                                wire:model="callout.incident_date"
                                id="callout.incident_date"
                                class="py-2 pr-4 pl-2 mt-2 w-full text-sm rounded-lg border border-gray-400 sm:text-base focus:outline-none focus:border-blue-400"
                            />
                            @error('callout.incident_date')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-2 w-full">
                            <label class="block text-sm font-medium text-gray-700" for="callout.ampds_code">
                                AMPDS Code
                            </label>

                            <input
                                type="text"
                                wire:model="callout.ampds_code"
                                id="callout.ampds_code"
                                class="py-2 pr-4 pl-2 mt-2 w-full text-sm rounded-lg border border-gray-400 sm:text-base focus:outline-none focus:border-blue-400"
                            />
                            @error('callout.ampds_code')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-2 w-full">
                            <label class="block text-sm font-medium text-gray-700" for="callout.age">
                                Age
                            </label>

                            <input
                                type="text"
                                wire:model="callout.age"
                                id="callout.age"
                                class="py-2 pr-4 pl-2 mt-2 w-full text-sm rounded-lg border border-gray-400 sm:text-base focus:outline-none focus:border-blue-400"
                            />
                            @error('callout.age')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-2 w-full">
                            <label class="block text-sm font-medium text-gray-700" for="callout.gender">
                                Gender
                            </label>

                            <select
                                wire:model="callout.gender"
                                id="callout.gender"
                                class="py-2 pr-4 pl-2 mt-2 w-full text-sm rounded-lg border border-gray-400 sm:text-base focus:outline-none focus:border-blue-400"
                            >
                                <option disabled value="">Select</option>
                                <option value="Unknown">Unknown</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            @error('callout.gender')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-2 w-full">
                            <label class="block text-sm font-medium text-gray-700" for="callout.attended">
                                Attended
                            </label>

                            <select
                                wire:model="callout.attended"
                                id="callout.attended"
                                class="py-2 pr-4 pl-2 mt-2 w-full text-sm rounded-lg border border-gray-400 sm:text-base focus:outline-none focus:border-blue-400"
                            >
                                <option disabled value="">Select</option>
                                <option value="No">No</option>
                                <option value="Yes">Yes</option>
                            </select>
                            @error('callout.attended')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="@if(!$showAdditionalFormFields) hidden @endif w-full">
                            <div class="mb-2 w-full">
                                <label class="block text-sm font-medium text-gray-700" for="callout.ohca_at_scene">
                                    OHCA At Scene
                                </label>

                                <select
                                    wire:model="callout.ohca_at_scene"
                                    id="callout.ohca_at_scene"
                                    class="py-2 pr-4 pl-2 mt-2 w-full text-sm rounded-lg border border-gray-400 sm:text-base focus:outline-none focus:border-blue-400"
                                >
                                    <option disabled value="">Select</option>
                                    <option value="No">No</option>
                                    <option value="Yes">Yes</option>
                                    <option value="Unknown">Unknown</option>
                                </select>
                                @error('callout.ohca_at_scene')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-2 w-full">
                                <label class="block text-sm font-medium text-gray-700" for="callout.bystander_cpr">
                                    Bystander CPR
                                </label>

                                <select
                                    wire:model="callout.bystander_cpr"
                                    id="callout.bystander_cpr"
                                    class="py-2 pr-4 pl-2 mt-2 w-full text-sm rounded-lg border border-gray-400 sm:text-base focus:outline-none focus:border-blue-400"
                                >
                                    <option disabled value="">Select</option>
                                    <option value="No">No</option>
                                    <option value="Yes">Yes</option>
                                    <option value="Unknown">Unknown</option>
                                </select>
                                @error('callout.bystander_cpr')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-2 w-full">
                                <label class="block text-sm font-medium text-gray-700" for="callout.source_of_aed">
                                    AED Source
                                </label>

                                <select
                                    wire:model="callout.source_of_aed"
                                    id="callout.source_of_aed"
                                    class="py-2 pr-4 pl-2 mt-2 w-full text-sm rounded-lg border border-gray-400 sm:text-base focus:outline-none focus:border-blue-400"
                                >
                                    <option disabled value="">Select</option>
                                    @foreach($aedSources as $source)
                                        <option value="{{ $source }}">{{ $source }}</option>
                                    @endforeach
                                </select>
                                @error('callout.source_of_aed')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-2 w-full">
                                <label class="block text-sm font-medium text-gray-700" for="callout.number_of_shocks_given">
                                    Number of Shocks Given
                                </label>

                                <input
                                    type="number"
                                    wire:model="callout.number_of_shocks_given"
                                    id="callout.number_of_shocks_given"
                                    class="py-2 pr-4 pl-2 mt-2 w-full text-sm rounded-lg border border-gray-400 sm:text-base focus:outline-none focus:border-blue-400"
                                />
                                @error('callout.number_of_shocks_given')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-2 w-full">
                                <label class="block text-sm font-medium text-gray-700" for="callout.rosc_achieved">
                                    ROSC Achieved
                                </label>

                                <select
                                    wire:model="callout.rosc_achieved"
                                    id="callout.rosc_achieved"
                                    class="py-2 pr-4 pl-2 mt-2 w-full text-sm rounded-lg border border-gray-400 sm:text-base focus:outline-none focus:border-blue-400"
                                >
                                    <option disabled value="">Select</option>
                                    <option value="No">No</option>
                                    <option value="Yes">Yes</option>
                                    <option value="N/A">N/A</option>
                                    <option value="Unknown">Unknown</option>
                                </select>
                                @error('callout.rosc_achieved')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-2 w-full">
                                <label class="block text-sm font-medium text-gray-700" for="callout.patient_transported">
                                    Patient Transported
                                </label>

                                <select
                                    wire:model="callout.patient_transported"
                                    id="callout.patient_transported"
                                    class="py-2 pr-4 pl-2 mt-2 w-full text-sm rounded-lg border border-gray-400 sm:text-base focus:outline-none focus:border-blue-400"
                                >
                                    <option disabled value="">Select</option>
                                    <option value="No">No</option>
                                    <option value="Yes">Yes</option>
                                    <option value="Unknown">Unknown</option>
                                </select>
                                @error('callout.patient_transported')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-2 w-full">
                                <label class="block text-sm font-medium text-gray-700" for="callout.responders_at_scene">
                                    Responders At Scene
                                </label>

                                <input
                                    type="number"
                                    wire:model="callout.responders_at_scene"
                                    id="callout.responders_at_scene"
                                    class="py-2 pr-4 pl-2 mt-2 w-full text-sm rounded-lg border border-gray-400 sm:text-base focus:outline-none focus:border-blue-400"
                                />
                                @error('callout.responders_at_scene')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-2 w-full">
                                <label class="block text-sm font-medium text-gray-700" for="callout.ppe_kits_used">
                                    PPE Kits Used
                                </label>

                                <input
                                    type="number"
                                    wire:model="callout.ppe_kits_used"
                                    id="callout.ppe_kits_used"
                                    class="py-2 pr-4 pl-2 mt-2 w-full text-sm rounded-lg border border-gray-400 sm:text-base focus:outline-none focus:border-blue-400"
                                />
                                @error('callout.ppe_kits_used')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-2 w-full">
                                <label class="block text-sm font-medium text-gray-700" for="callout.waste_disposal">
                                    Waste Disposal
                                </label>

                                <select
                                    wire:model="callout.waste_disposal"
                                    id="callout.waste_disposal"
                                    class="py-2 pr-4 pl-2 mt-2 w-full text-sm rounded-lg border border-gray-400 sm:text-base focus:outline-none focus:border-blue-400"
                                >
                                    <option disabled value="">Select</option>
                                    @foreach($wasteDisposalMethods as $source)
                                        <option value="{{ $source }}">{{ $source }}</option>
                                    @endforeach
                                </select>
                                @error('callout.waste_disposal')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-2 w-full">
                            <label class="block text-sm font-medium text-gray-700" for="callout.notes">
                                Notes
                            </label>

                            <textarea
                                wire:model="callout.notes"
                                id="callout.notes"
                                class="py-2 pr-4 pl-2 mt-2 w-full text-sm rounded-lg border border-gray-400 sm:text-base focus:outline-none focus:border-blue-400"
                            >
                            </textarea>
                            @error('callout.notes')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mt-4 ml-auto">
                            <button class="px-4 py-2 font-bold text-white bg-red-500 rounded hover:bg-red-700" type="submit">
                                Create
                            </button>
                            <button wire:click.prevent="$set('showCreateModal', false)" class="px-4 py-2 font-bold text-white bg-gray-500 rounded" type="button" data-dismiss="modal">
                                Close
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
