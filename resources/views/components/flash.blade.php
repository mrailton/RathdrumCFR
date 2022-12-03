@if(session()->has('success'))
    <div
        x-data="{show: true}"
        x-init="setTimeout(() => show = false, 4000)"
        x-show="show"
        class="fixed bg-green-500 text-white py-4 px-8 top-3 right-3 text-xl rounded-xl font-semibold"
    >
        <p>{{ session('success') }}</p>
    </div>
@endif
