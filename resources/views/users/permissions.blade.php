<x-app-layout>
{{--@dd($user->permissions, $permissions)--}}
    <div class="overflow-hidden bg-white shadow sm:rounded-lg">
        <div class="sm:flex sm:items-center py-5 px-4 sm:px-6 lg:px-8">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900">Manage Permissions for {{ $user->name }}</h1>
            </div>
        </div>

        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-gray-200">
                <form action="{{ route('users.permissions.update', ['user' => $user]) }}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <fieldset>
                            <div class="mt-4 divide-y divide-gray-200 border-b border-gray-200">
                                @if($errors->hasAny())

                                    @dd($errors)
                                @endif
                                @foreach($permissions as $permission)
                                    <div class="relative flex items-start py-4">
                                        <div class="min-w-0 flex-1 text-sm">
                                            <label for="permissions" class="select-none font-medium text-gray-700">{{ ucwords(str_replace('.', ' ', $permission->name)) }}</label>
                                        </div>
                                        <div class="ml-3 flex h-5 items-center">
                                            <input id="permissions" name="permissions[]" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" value="{{ $permission->id }}" @checked($user->can($permission->name))>
                                        </div>
                                    </div>
                                @endforeach
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
