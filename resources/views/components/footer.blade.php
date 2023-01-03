<footer class="py-12 px-4">
    <div aria-label="footer" class="focus:outline-none mx-auto container flex flex-col items-center justify-center">
        <div class="w-9/12 h-0.5 bg-gray-100 rounded-full"></div>
        <div class="text-black flex flex-col md:items-center f-f-l pt-3 text-center">
            <div class="mt-6 mb-2 text-base text-color f-f-l">
                &copy; Rathdrum Community First Responders {{ now()->year }}
            </div>
            <div class="text-sm text-color mb-2 f-f-l">
                <p class="focus:outline-none">Registered Charity Number: 20204442</p>
                <p class="focus:outline-none">Website by
                    <a href="https://markrailton.com" target="_blank">Mark Railton</a>
                </p>
                @guest()
                    <a href="{{ route('login.create') }}">Login</a>
                @endguest
                @auth()
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                @endauth
            </div>
        </div>
    </div>

    <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</footer>
