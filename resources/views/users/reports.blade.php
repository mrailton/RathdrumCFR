<x-app-layout>
    <div class="overflow-hidden bg-white shadow sm:rounded-lg">
        <div class="sm:flex sm:items-center py-5 px-4 sm:px-6 lg:px-8">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900">User Requested Reports</h1>
            </div>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-gray-200">
                <form action="{{ route('users.reports.store', ['user' => $user]) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <label class="text-base font-medium text-gray-900">CFR Certificate Expiry</label>
                        <fieldset class="mt-4">
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input id="yes" name="cfr_cert_expiry" type="radio" value="Yes" @checked($user->reports->cfr_cert_expiry === 1) class="h-4 w-4 border-gray-300 text-red-600 focus:ring-red-500">
                                    <label for="yes" class="ml-3 block text-sm font-medium text-gray-700">Yes</label>
                                </div>

                                <div class="flex items-center">
                                    <input id="no" name="cfr_cert_expiry" type="radio" value="No"  @checked($user->reports->cfr_cert_expiry === 0) class="h-4 w-4 border-gray-300 text-red-600 focus:ring-red-500">
                                    <label for="no" class="ml-3 block text-sm font-medium text-gray-700">No</label>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <label class="text-base font-medium text-gray-900">Garda Vetting Expiry</label>
                        <fieldset class="mt-4">
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input id="yes" name="garda_vetting_expiry" type="radio" value="Yes"  @checked($user->reports->garda_vetting_expiry === 1) class="h-4 w-4 border-gray-300 text-red-600 focus:ring-red-500">
                                    <label for="yes" class="ml-3 block text-sm font-medium text-gray-700">Yes</label>
                                </div>

                                <div class="flex items-center">
                                    <input id="no" name="garda_vetting_expiry" type="radio" value="No" @checked($user->reports->garda_vetting_expiry === 0) class="h-4 w-4 border-gray-300 text-red-600 focus:ring-red-500">
                                    <label for="no" class="ml-3 block text-sm font-medium text-gray-700">No</label>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <label class="text-base font-medium text-gray-900">Defib Inspection</label>
                        <fieldset class="mt-4">
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input id="yes" name="defib_inspection" type="radio" value="Yes"  @checked($user->reports->defib_inspection === 1) class="h-4 w-4 border-gray-300 text-red-600 focus:ring-red-500">
                                    <label for="yes" class="ml-3 block text-sm font-medium text-gray-700">Yes</label>
                                </div>

                                <div class="flex items-center">
                                    <input id="no" name="defib_inspection" type="radio" value="No" @checked($user->reports->defib_inspection === 0) class="h-4 w-4 border-gray-300 text-red-600 focus:ring-red-500">
                                    <label for="no" class="ml-3 block text-sm font-medium text-gray-700">No</label>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <label class="text-base font-medium text-gray-900">Defib Battery Expiry</label>
                        <fieldset class="mt-4">
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input id="yes" name="defib_battery_expiry" type="radio" value="Yes"  @checked($user->reports->defib_battery_expiry === 1) class="h-4 w-4 border-gray-300 text-red-600 focus:ring-red-500">
                                    <label for="yes" class="ml-3 block text-sm font-medium text-gray-700">Yes</label>
                                </div>

                                <div class="flex items-center">
                                    <input id="no" name="defib_battery_expiry" type="radio" value="No" @checked($user->reports->defib_battery_expiry === 0) class="h-4 w-4 border-gray-300 text-red-600 focus:ring-red-500">
                                    <label for="no" class="ml-3 block text-sm font-medium text-gray-700">No</label>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <label class="text-base font-medium text-gray-900">Defib Pad Expiry</label>
                        <fieldset class="mt-4">
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input id="yes" name="defib_pad_expiry" type="radio" value="Yes"  @checked($user->reports->defib_pad_expiry === 1) class="h-4 w-4 border-gray-300 text-red-600 focus:ring-red-500">
                                    <label for="yes" class="ml-3 block text-sm font-medium text-gray-700">Yes</label>
                                </div>

                                <div class="flex items-center">
                                    <input id="no" name="defib_pad_expiry" type="radio" value="No" @checked($user->reports->defib_pad_expiry === 0) class="h-4 w-4 border-gray-300 text-red-600 focus:ring-red-500">
                                    <label for="no" class="ml-3 block text-sm font-medium text-gray-700">No</label>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-red-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">Update</button>
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <a href="{{ route('users.show', ['user' => $user]) }}"><button type="button" class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">Cancel</button></a>
                        </dd>
                    </div>
                </form>
            </dl>
        </div>
    </div>
</x-app-layout>
