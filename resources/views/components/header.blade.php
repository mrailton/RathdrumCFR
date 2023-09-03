<header class="bg-red-600">
    <nav class="border-b">
        <div x-data="{showMenu : false}" class="container max-w-screen-2xl mx-auto flex justify-between h-14 sm:text-gray-700 md:text-white">
            <a href="{{ route('index') }}" class="flex items-center cursor-pointer hover:bg-white px-2 ml-3">
                <div class="font-semibold text-md md:text-2xl text-white hover:text-red-600">Rathdrum Community First Responders</div>
            </a>

            <button @click="showMenu = !showMenu" class="block md:hidden text-gray-700 p-2 rounded hover:border focus:border focus:bg-gray-400 my-2 mr-5" type="button" aria-controls="navbar-main" aria-expanded="false" aria-label="Toggle navigation">
                <svg class="w-5 h-5" fill="none" stroke="white" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>

            <ul class="md:flex text-base mr-3 origin-top" :class="{ 'block absolute top-14 border-b bg-white w-full p-2': showMenu, 'hidden': !showMenu}" id="navbar-main" x-cloak>
                <li class="{{ (request()->is('/')) ? 'bg-white text-red-600' : '' }} px-3 cursor-pointer hover:bg-white flex items-center hover:text-red-600" :class="showMenu && 'py-1'">
                    <a href="{{ route('index') }}">Home</a>
                </li>

                <li class="{{ (request()->is('contact')) ? 'bg-white text-red-600' : '' }} px-3 cursor-pointer hover:bg-white flex items-center hover:text-red-600" :class="showMenu && 'py-1'">
                    <a href="{{ route('contact.create') }}">Contact Us</a>
                </li>

                @auth()
                    <li class="px-3 cursor-pointer hover:bg-white flex items-center hover:text-red-600" :class="showMenu && 'py-1'">
                        <a href="{{ route('filament.admin.pages.dashboard') }}">Admin</a>
                    </li>
                @endauth
            </ul>
        </div>
    </nav>
</header>
