<x-app-layout>
    <div class="overflow-hidden bg-white shadow sm:rounded-lg">
        <div class="sm:flex sm:items-center py-5 px-4 sm:px-6 lg:px-8">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900">Update Defib</h1>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            </div>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <form action="{{ route('defibs.update', ['defib' => $defib->id]) }}" method="post">
                @csrf
                @method('PUT')
                <dl class="sm:divide-y sm:divide-gray-200">
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="name">Name</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="text" name="name" id="name" class="block @error('name') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" value="{{ $defib->name }}">
                            @error('name')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="location">Location</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="text" name="location" id="location" class="block @error('location') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" value="{{ $defib->location }}">
                            @error('location')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="coordinates">Coordinates</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="text" name="coordinates" id="coordinates" class="block @error('coordinates') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" value="{{ $defib->coordinates }}">
                            @error('coordinates')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="display_on_map">Display On Map</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <select name="display_on_map" id="display_on_map" class="block rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                                <option value="0" @if(!$defib->display_on_map) selected="selected" @endif>No</option>
                                <option value="1" @if($defib->display_on_map) selected="selected" @endif>Yes</option>
                            </select>
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="model">Model</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="text" name="model" id="model" class="block @error('model') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" value="{{ $defib->model }}">
                            @error('model')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="serial">Serial Number</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="text" name="serial" id="serial" class="block @error('serial') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" value="{{ $defib->serial }}">
                            @error('serial')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="owner">Owner</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="text" name="owner" id="owner" class="block @error('owner') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" value="{{ $defib->owner }}">
                            @error('owner')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="last_inspected_by">Last Inspected By</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="text" name="last_inspected_by" id="last_inspected_by" class="block @error('last_inspected_by') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" value="{{ $defib->last_inspected_by }}">
                            @error('last_inspected_by')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="last_inspected_at">Last Inspected On</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="date" name="last_inspected_at" id="last_inspected_at" class="block @error('last_inspected_at') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" value="{{ $defib->last_inspected_at?->format('Y-m-d') }}">
                            @error('last_inspected_at')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="last_serviced_at">Last Serviced On</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="date" name="last_serviced_at" id="last_serviced_at" class="block @error('last_serviced_at') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" value="{{ $defib->last_serviced_at?->format('Y-m-d') }}">
                            @error('last_serviced_at')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="pads_expire_at">Pads Expire On</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="date" name="pads_expire_at" id="pads_expire_at" class="block @error('pads_expire_at') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" value="{{ $defib->pads_expire_at?->format('Y-m-d') }}">
                            @error('pads_expire_at')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="battery_expires_at">Battery Expires On</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="date" name="battery_expires_at" id="battery_expires_at" class="block @error('battery_expires_at') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" value="{{ $defib->battery_expires_at?->format('Y-m-d') }}">
                            @error('battery_expires_at')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-red-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">Update</button>
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <a href="{{ route('defibs.view', ['defib' => $defib->id]) }}"><button type="button" class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">Cancel</button></a>
                        </dd>
                    </div>
                </dl>
            </form>
        </div>
    </div>
</x-app-layout>
