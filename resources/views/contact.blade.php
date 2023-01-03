<x-app-layout>
    <div class="bg-white px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-xl">
            <div class="text-center">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Contact Us</h2>
                <p class="mt-4 text-lg leading-6 text-gray-500">If you'd like to get in touch with us, please fill in the form below and someone will get back to you as soon as possible.</p>
                <p class="mt-4 text-lg leading-6 text-gray-500">Please bear in mind that we are a voluntary organisation and as such there may be a delay in getting back to you.</p>
                <p class="mt-4 text-lg leading-6 text-gray-500">Remember, in a medical emergency you should call 999 or 112!</p>
            </div>
            <div class="mt-12">
                <form action="{{ route('contact.store') }}" method="POST" class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-8">
                    @csrf
                    <x-honeypot />
                    <div class="sm:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <div class="mt-1">
                            <input type="text" name="name" id="name" autocomplete="name" class="block w-full rounded-md border-gray-300 py-3 px-4 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" autocomplete="email" class="block w-full rounded-md border-gray-300 py-3 px-4 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <div class="relative mt-1 rounded-md shadow-sm">
                            <input type="text" name="phone" id="phone" autocomplete="tel" class="block w-full rounded-md border-gray-300 py-3 px-4 focus:border-red-500 focus:ring-red-500" placeholder="083 123 4567" required>
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                        <div class="mt-1">
                            <textarea id="message" name="message" rows="4" class="block w-full rounded-md border-gray-300 py-3 px-4 shadow-sm focus:border-red-500 focus:ring-red-500" required></textarea>
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-md border border-transparent bg-red-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">Let's talk</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
