<x-app-layout>
    <div class="overflow-hidden bg-white shadow sm:rounded-lg">
        <div class="sm:flex sm:items-center py-5 px-4 sm:px-6 lg:px-8">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900">Log Callout</h1>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            </div>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <form action="{{ route('callouts.store') }}" method="post" x-data="{ showAdditionalFields: 'No' }">
                @csrf
                <dl class="sm:divide-y sm:divide-gray-200">
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="incident_number">Incident Number</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0"><input type="text" name="incident_number" id="incident_number" class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"></dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="incident_date">Incident Date</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0"><input type="datetime-local" name="incident_date" id="incident_date" class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"></dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="ampds_code">AMPDS Code</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0"><input type="text" name="ampds_code" id="ampds_code" class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"></dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="age">Age</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0"><input type="text" name="age" id="age" class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"></dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="gender">Gender</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <select name="gender" id="gender" class="block rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                                <option value="Unknown">Unknown</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="attended">Attended?</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <select x-model="showAdditionalFields" name="attended" id="attended" class="block rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6" x-show="showAdditionalFields === 'Yes'">
                        <dt class="text-sm font-medium text-gray-500"><label for="ohca_at_scene">OHCA At Scene</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <select name="ohca_at_scene" id="ohca_at_scene" class="block rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                                <option value="No">No</option>
                                <option value="Yes">Yes</option>
                                <option value="Unknown">Unknown</option>
                            </select>
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6" x-show="showAdditionalFields === 'Yes'">
                        <dt class="text-sm font-medium text-gray-500"><label for="bystander_cpr">Bystander CPR</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <select name="bystander_cpr" id="bystander_cpr" class="block rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                                <option value="No">No</option>
                                <option value="Yes">Yes</option>
                                <option value="Unknown">Unknown</option>
                            </select>
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6" x-show="showAdditionalFields === 'Yes'">
                        <dt class="text-sm font-medium text-gray-500"><label for="source_of_aed">AED Source</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <select name="source_of_aed" id="source_of_aed" class="block rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                                <option value="CFR">CFR</option>
                                <option value="PAD">PAD</option>
                                <option value="NAS">NAS</option>
                                <option value="Fire">Fire</option>
                                <option value="Garda">Garda</option>
                                <option value="Other">Other</option>
                            </select>
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6" x-show="showAdditionalFields === 'Yes'">
                        <dt class="text-sm font-medium text-gray-500"><label for="number_of_shocks_given">Number of Shocks Given</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0"><input type="number" name="number_of_shocks_given" id="number_of_shocks_given" class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"></dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6" x-show="showAdditionalFields === 'Yes'">
                        <dt class="text-sm font-medium text-gray-500"><label for="rosc_achieved">ROSC Achieved</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <select name="rosc_achieved" id="rosc_achieved" class="block rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                                <option value="No">No</option>
                                <option value="Yes">Yes</option>
                                <option value="Unknown">Unknown</option>
                            </select>
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6" x-show="showAdditionalFields === 'Yes'">
                        <dt class="text-sm font-medium text-gray-500"><label for="patient_transported">Patient Transported</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <select name="patient_transported" id="patient_transported" class="block rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                                <option value="No">No</option>
                                <option value="Yes">Yes</option>
                                <option value="Unknown">Unknown</option>
                            </select>
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6" x-show="showAdditionalFields === 'Yes'">
                        <dt class="text-sm font-medium text-gray-500"><label for="responders_at_scene">Responders At Scene</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0"><input type="number" name="responders_at_scene" id="responders_at_scene" class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"></dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6" x-show="showAdditionalFields === 'Yes'">
                        <dt class="text-sm font-medium text-gray-500"><label for="ppe_kits_used">PPE Kits Used</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0"><input type="number" name="ppe_kits_used" id="ppe_kits_used" class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"></dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6" x-show="showAdditionalFields === 'Yes'">
                        <dt class="text-sm font-medium text-gray-500"><label for="waste_disposal">Waste Disposal</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <select name="waste_disposal" id="waste_disposal" class="block rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                                <option value="NAS Crew">NAS Crew</option>
                                <option value="DFB Crew">DFB Crew</option>
                                <option value="NAS Station">NAS Station</option>
                                <option value="Hospital">Hospital</option>
                                <option value="Responder">Responder</option>
                                <option value="Other">Other</option>
                            </select>
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="notes">Notes</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0"><textarea rows="5" name="notes" id="notes" class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"></textarea></dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-red-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">Log Callout</button>
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <button type="button" class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">Cancel</button>
                        </dd>
                    </div>
                </dl>
            </form>
        </div>
    </div>
</x-app-layout>
