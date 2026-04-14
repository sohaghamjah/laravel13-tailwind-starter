{{-- resources/views/components/badge.blade.php --}}
@props([
    'type' => 'primary',
    'size' => 'sm',
    'rounded' => 'full',
    'icon' => null,
])

@php
    $colors = [
        'primary' => [
            'bg' => 'bg-brand-50',
            'text' => 'text-brand-600',
            'dark_bg' => 'dark:bg-brand-500/15',
            'dark_text' => 'dark:text-brand-400',
        ],
        'success' => [
            'bg' => 'bg-success-50',
            'text' => 'text-success-600',
            'dark_bg' => 'dark:bg-success-500/15',
            'dark_text' => 'dark:text-success-500',
        ],
        'error' => [
            'bg' => 'bg-error-50',
            'text' => 'text-error-600',
            'dark_bg' => 'dark:bg-error-500/15',
            'dark_text' => 'dark:text-error-500',
        ],
        'warning' => [
            'bg' => 'bg-warning-50',
            'text' => 'text-warning-600',
            'dark_bg' => 'dark:bg-warning-500/15',
            'dark_text' => 'dark:text-orange-400',
        ],
        'info' => [
            'bg' => 'bg-blue-light-50',
            'text' => 'text-blue-light-600',
            'dark_bg' => 'dark:bg-blue-light-500/15',
            'dark_text' => 'dark:text-blue-light-500',
        ],
        'light' => [
            'bg' => 'bg-gray-100',
            'text' => 'text-gray-700',
            'dark_bg' => 'dark:bg-white/5',
            'dark_text' => 'dark:text-white/80',
        ],
        'dark' => [
            'bg' => 'bg-gray-500',
            'text' => 'text-white',
            'dark_bg' => 'dark:bg-white/5',
            'dark_text' => 'dark:text-white',
        ],
    ];

    $sizes = [
        'sm' => 'px-2.5 py-0.5 text-xs',
        'md' => 'px-3 py-1 text-sm',
    ];

    $roundedClasses = [
        'full' => 'rounded-full',
        'lg' => 'rounded-lg',
        'md' => 'rounded-md',
    ];

    $color = $colors[$type] ?? $colors['primary'];
    $sizeClass = $sizes[$size] ?? $sizes['sm'];
    $roundedClass = $roundedClasses[$rounded] ?? $roundedClasses['full'];

    $baseClasses = "inline-flex items-center justify-center gap-1 font-medium {$color['bg']} {$color['text']} {$color['dark_bg']} {$color['dark_text']} {$sizeClass} {$roundedClass}";
@endphp

<span {{ $attributes->class([$baseClasses]) }}>
    @if($icon)
        <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>
    @endif
    {{ $slot }}
</span>
