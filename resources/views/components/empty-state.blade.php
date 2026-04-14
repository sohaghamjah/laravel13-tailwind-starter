{{-- resources/views/components/empty-state.blade.php --}}
@props([
    'title' => 'No Data Found',
    'description' => null,
    'icon' => 'users',
    'buttonText' => 'Create First',
    'buttonLink' => null,
    'buttonIcon' => 'plus',
    'colspan' => null,
])

<td @if($colspan) colspan="{{ $colspan }}" @endif class="px-5 py-12 text-center sm:px-6">
    <div class="flex flex-col items-center justify-center">
        <i data-lucide="{{ $icon }}" class="w-12 h-12 text-gray-400 dark:text-gray-600"></i>

        <p class="mt-3 text-gray-500 dark:text-gray-400">{{ $title }}</p>

        @if($description)
            <p class="mt-1 text-sm text-gray-400 dark:text-gray-500">{{ $description }}</p>
        @endif

        @if($buttonLink)
            <a href="{{ $buttonLink }}"
               class="mt-3 inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                <i data-lucide="{{ $buttonIcon }}" class="w-4 h-4"></i>
                <span>{{ $buttonText }}</span>
            </a>
        @endif
    </div>
</td>
