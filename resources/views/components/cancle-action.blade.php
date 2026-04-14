@props([
    'cancelRoute' => null,
    'cancelText' => 'Cancel',
    'icon' => 'save',
])

<div class="flex items-center gap-3">

    @if($cancelRoute)
        <a href="{{ $cancelRoute }}"
            class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 transition bg-white border border-gray-300 rounded-lg shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
            {{ $cancelText }}
        </a>
    @endif

</div>
