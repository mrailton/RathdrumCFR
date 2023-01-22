<x-app-layout>
    <div class="overflow-hidden bg-white shadow sm:rounded-lg">
        <div class="sm:flex sm:items-center py-5 px-4 sm:px-6 lg:px-8">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900">Add Defib Inspection</h1>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            </div>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <form action="{{ route('defibs.inspections.store', ['id' => $defib->id]) }}" method="post">
                @csrf
                <dl class="sm:divide-y sm:divide-gray-200">
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="member_id">Inspected By</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <select type="text" name="member_id" id="member_id" class="block @error('member_id') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                                @endforeach
                            </select>
                            @error('member_id')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="inspected_at">Inspected On</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="date" name="inspected_at" id="inspected_at" class="block @error('inspected_at') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                            @error('inspected_at')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="pads_expire_at">Pads Expire On</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="date" name="pads_expire_at" id="pads_expire_at" class="block @error('pads_expire_at') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                            @error('pads_expire_at')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="battery_expires_at">Battery Expires On</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="date" name="battery_expires_at" id="battery_expires_at" class="block @error('battery_expires_at') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                            @error('battery_expires_at')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="notes">Notes</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <textarea rows="5" name="notes" id="notes" class="block @error('notes') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"></textarea>
                            @error('notes')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-red-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">Add Inspection</button>
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <a href="{{ route('defibs.view', ['id' => $defib->id]) }}">
                                <button type="button" class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">Cancel</button>
                            </a>
                        </dd>
                    </div>
                </dl>
            </form>
        </div>
    </div>
</x-app-layout>
