<x-app-layout>
    <form class="space-y-8 divide-y divide-gray-200" action="{{ route('defibs.store') }}" method="post">
        @csrf()
        <div class="space-y-8 divide-y divide-gray-200 sm:space-y-5">
            <div class="space-y-6 pt-8 sm:space-y-5 sm:pt-10">
                <div>
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Add Defib</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Please enter all available information.</p>
                </div>
                <div class="space-y-6 sm:space-y-5">
                    <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="name" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">Name</label>
                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <input type="text" name="name" id="name" class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="location" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">Location</label>
                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <input type="text" name="location" id="location" class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="coordinates" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">Coordinates</label>
                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <input type="text" name="coordinates" id="coordinates" class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="display_on_map" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">Display On Map</label>
                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <input type="checkbox" name="display_on_map" id="display_on_map" class="block border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="model" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">Model</label>
                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <input type="text" name="model" id="model" class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="serial" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">Serial Number</label>
                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <input type="text" name="serial" id="serial" class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="owner" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">Owner</label>
                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <input type="text" name="owner" id="owner" class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="last_inspected_by" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">Last Inspected By</label>
                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <input type="text" name="last_inspected_by" id="last_inspected_by" class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="last_inspected_at" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">Last Inspected On</label>
                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <input type="date" name="last_inspected_at" id="last_inspected_at" class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="last_serviced_at" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">Last Serviced On</label>
                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <input type="date" name="last_serviced_at" id="last_serviced_at" class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="pads_expire_at" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">Pads Expire On</label>
                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <input type="date" name="pads_expire_at" id="pads_expire_at" class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                        <label for="battery_expires_at" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">Battery Expires On</label>
                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                            <input type="date" name="battery_expires_at" id="battery_expires_at" class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-5">
            <div class="flex justify-end">
                <button type="button" class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">Cancel</button>
                <button type="submit" class="ml-3 inline-flex justify-center rounded-md border border-transparent bg-red-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">Add</button>
            </div>
        </div>
    </form>
</x-app-layout>
