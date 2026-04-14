{{-- resources/views/components/icon-button.blade.php --}}
@props([
    'icon' => 'plus',
    'variant' => 'primary',
    'href' => null,
    'size' => 'md',
    'label' => null,  // Added label prop
])

@php
    $variants = [
        'primary' => 'bg-brand-500 text-white hover:bg-brand-600',
        'secondary' => 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700',
        'ghost' => 'bg-transparent text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800',
        'danger' => 'bg-red-500 text-white hover:bg-red-600',
    ];

    $sizes = [
        'sm' => 'p-1.5',
        'md' => 'p-2',
        'lg' => 'p-2.5',
    ];

    $iconSizes = [
        'sm' => 'w-3.5 h-3.5',
        'md' => 'w-4 h-4',
        'lg' => 'w-5 h-5',
    ];

    $classes = "inline-flex items-center justify-center gap-2 rounded-lg transition {$variants[$variant]} {$sizes[$size]} " . ($attributes->get('class') ?? '');
@endphp

@if($href)
    <a href="{{ $href }}" class="{{ $classes }}">
        <i data-lucide="{{ $icon }}" class="{{ $iconSizes[$size] }}"></i>
        @if($label)
            <span>{{ $label }}</span>
        @else
            {{ $slot }}
        @endif
    </a>
@else
    <button type="button" class="{{ $classes }}">
        <i data-lucide="{{ $icon }}" class="{{ $iconSizes[$size] }}"></i>
        @if($label)
            <span>{{ $label }}</span>
        @else
            {{ $slot }}
        @endif
    </button>
@endif
