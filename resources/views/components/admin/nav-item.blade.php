@props(['active' => false])

<li>
    <a href="{{ $attributes->get('href') }}" 
       class="{{ $active 
           ? 'bg-gray-50 dark:bg-gray-800 text-red-600 dark:text-red-400' 
           : 'text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 hover:bg-gray-50 dark:hover:bg-gray-800' 
       }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
        <span class="{{ $active ? 'text-red-600 dark:text-red-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-red-600 dark:group-hover:text-red-400' }}">
            {{ $icon }}
        </span>
        {{ $slot }}
    </a>
</li>
