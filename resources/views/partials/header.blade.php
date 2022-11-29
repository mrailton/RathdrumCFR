<header class="bg-red-700">
    <nav class="container px-6 py-8 mx-auto text-gray-100 md:flex md:justify-between md:items-center">
        <div class="flex items-center justify-between">
            <a href="{{ route('index') }}" class="font-semibold text-md md:text-2xl">
                Rathdrum Community First Responders
            </a>
            <!-- Mobile menu button -->
            <div class="flex md:hidden">
                <button type="button"
                        class="focus:outline-none">
                    <svg viewBox="0 0 24 24" class="w-6 h-6 fill-current">
                        <path fill-rule="evenodd"
                              d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z"></path>
                    </svg>
                </button>
            </div>
        </div>

        <ul class="flex-col mt-8 space-y-4 font-semibold md:flex md:space-y-0 md:flex-row md:items-center md:space-x-10 md:mt-0 text-md md:text-lg">
            <li>
                <a href="{{ route('index') }}">Home</a>
            </li>
            <li>
                <a href="#">Contact Us</a>
            </li>
            @can('defib.list')
                <li>
                    <a href="{{ route('defibs.list') }}">Defibs</a>
                </li>
            @endcan
            @can('member.list')
                <li>
                    <a href="{{ route('members.list') }}">Members</a>
                </li>
            @endcan
        </ul>
    </nav>
</header>
