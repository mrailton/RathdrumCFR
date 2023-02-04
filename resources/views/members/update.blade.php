<x-app-layout>
    <div class="overflow-hidden bg-white shadow sm:rounded-lg">
        <div class="sm:flex sm:items-center py-5 px-4 sm:px-6 lg:px-8">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900">Update Member</h1>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            </div>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <form action="{{ route('members.update', ['member' => $member]) }}" method="post">
                @csrf
                @method('PUT')
                <dl class="sm:divide-y sm:divide-gray-200">
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="name">Name</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="text" name="name" id="name" class="block @error('name') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" value="{{ $member->name }}">
                            @error('name')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="title">Role</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="text" name="title" id="title" class="block @error('title') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" value="{{ $member->title }}">
                            @error('role')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="status">Status</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <select type="text" name="status" id="status" class="block @error('status') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                                <option value="active" @if($member->status == 'active') selected="selected" @endif>Active</option>
                                <option value="inactive" @if($member->status == 'inactive') selected="selected" @endif>Inactive</option>
                            </select>
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="phone">Phone Number</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="text" name="phone" id="phone" class="block @error('phone') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" value="{{ $member->phone }}">
                            @error('phone')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="email">Email Address</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="email" name="email" id="email" class="block @error('email') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" value="{{ $member->email }}">
                            @error('email')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="address_1">Address 1</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="text" name="address_1" id="address_1" class="block @error('address_1') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" value="{{ $member->address_1 }}">
                            @error('address_1')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="address_2">Address 2</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="text" name="address_2" id="address_2" class="block @error('address_2') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" value="{{ $member->address_2 }}">
                            @error('address_2')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="eircode">Eircode</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="text" name="eircode" id="eircode" class="block @error('eircode') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" value="{{ $member->eircode }}">
                            @error('eircode')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="cfr_certificate_number">CFR Certificate Number</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="text" name="cfr_certificate_number" id="cfr_certificate_number" class="block @error('cfr_certificate_number') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" value="{{ $member->cfr_certificate_number }}">
                            @error('cfr_certificate_number')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="cfr_certificate_expiry">CFR Certificate Expiry</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="date" name="cfr_certificate_expiry" id="cfr_certificate_expiry" class="block @error('cfr_certificate_expiry') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" value="{{ $member->cfr_certificate_expiry?->format('Y-m-d') }}">
                            @error('cfr_certificate_expiry')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="garda_vetting_id">Garda Vetting ID</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="text" name="garda_vetting_id" id="garda_vetting_id" class="block @error('garda_vetting_id') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" value="{{ $member->garda_vetting_id }}">
                            @error('garda_vetting_id')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="garda_vetting_date">Garda Vetting Date</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="date" name="garda_vetting_date" id="garda_vetting_date" class="block @error('garda_vetting_date') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" value="{{ $member->garda_vetting_date?->format('Y-m-d') }}">
                            @error('garda_vetting_date')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="volunteer_declaration">Volunteer Declaration Signed</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="date" name="volunteer_declaration" id="volunteer_declaration" class="block @error('volunteer_declaration') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" value="{{ $member->volunteer_declaration?->format('Y-m-d') }}">
                            @error('volunteer_declaration')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="cism_completed">CISM Completed</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="date" name="cism_completed" id="cism_completed" class="block @error('cism_completed') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" value="{{ $member->cism_completed?->format('Y-m-d') }}">
                            @error('cism_completed')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="child_first_completed">Children First Completed</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="date" name="child_first_completed" id="child_first_completed" class="block @error('child_first_completed') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" value="{{ $member->child_first_completed?->format('Y-m-d') }}">
                            @error('child_first_completed')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="ppe_community_completed">PPE Community Completed</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="date" name="ppe_community_completed" id="ppe_community_completed" class="block @error('ppe_community_completed') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" value="{{ $member->ppe_community_completed?->format('Y-m-d') }}">
                            @error('ppe_community_completed')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="ppe_acute_completed">PPE Acute Completed</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="date" name="ppe_acute_completed" id="ppe_acute_completed" class="block @error('ppe_acute_completed') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" value="{{ $member->ppe_acute_completed?->format('Y-m-d') }}">
                            @error('ppe_acute_completed')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="hand_hygiene_completed">Hand Hygiene Completed</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="date" name="hand_hygiene_completed" id="hand_hygiene_completed" class="block @error('hand_hygiene_completed') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" value="{{ $member->hand_hygiene_completed?->format('Y-m-d') }}">
                            @error('hand_hygiene_completed')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="hiqa_completed">HIQA Completed</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="date" name="hiqa_completed" id="hiqa_completed" class="block @error('hiqa_completed') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" value="{{ $member->hiqa_completed?->format('Y-m-d') }}">
                            @error('hiqa_completed')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="covid_return_completed">COVID Return Completed</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="date" name="covid_return_completed" id="covid_return_completed" class="block @error('covid_return_completed') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" value="{{ $member->covid_return_completed?->format('Y-m-d') }}">
                            @error('covid_return_completed')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"><label for="ppe_assessment_completed">PPE Assessment Completed</label></dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <input type="date" name="ppe_assessment_completed" id="ppe_assessment_completed" class="block @error('ppe_assessment_completed') border-red-500 @enderror w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" value="{{ $member->ppe_assessment_completed?->format('Y-m-d') }}">
                            @error('ppe_assessment_completed')
                                <div class="text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-red-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">Update</button>
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <a href="{{ route('members.view', ['member' => $member]) }}"><button type="button" class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">Cancel</button></a>
                        </dd>
                    </div>
                </dl>
            </form>
        </div>
    </div>
</x-app-layout>
