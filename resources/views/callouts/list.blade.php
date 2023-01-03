<x-app-layout>
    <div class="overflow-hidden bg-white shadow sm:rounded-lg px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center py-5">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900">Callouts</h1>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                @can('defib.create')
                    <a href="{{ route('callouts.create') }}">
                        <button type="button" class="inline-flex items-center justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:w-auto">Log Callout</button>
                    </a>
                @endcan
            </div>
        </div>
        <div class="flex flex-col">
            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle">
                    <div class="overflow-hidden shadow-sm ring-1 ring-black ring-opacity-5">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 lg:pl-8">Incident Number</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">AMPDS Code</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Incident Date</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6 lg:pr-8">
                                    <span class="sr-only">View</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($callouts as $callout)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $callout->incident_number }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $callout->ampds_code }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $callout->incident_date }}</td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <a href="{{ route('callouts.show', ['callout' => $callout]) }}" class="text-red-600 hover:text-red-900">View<span class="sr-only">, {{ $callout->incident_number }}</span></a>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
