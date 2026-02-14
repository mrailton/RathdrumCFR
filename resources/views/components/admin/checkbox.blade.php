@props([
    'label' => '',
    'name' => '',
    'checked' => false,
    'help' => '',
])

<div class="flex items-start">
    <div class="flex h-6 items-center">
        <input 
            type="checkbox" 
            name="{{ $name }}" 
            id="{{ $name }}"
            value="1"
            {{ old($name, $checked) ? 'checked' : '' }}
            {{ $attributes->merge(['class' => 'h-4 w-4 rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-red-600 focus:ring-red-600']) }}
        >
    </div>
    @if($label || $help)
        <div class="ml-3 text-sm leading-6">
            @if($label)
                <label for="{{ $name }}" class="font-medium text-gray-900 dark:text-white">
                    {{ $label }}
                </label>
            @endif
            @if($help)
                <p class="text-gray-500 dark:text-gray-400">{{ $help }}</p>
            @endif
        </div>
    @endif
</div>
@error($name)
    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
@enderror
