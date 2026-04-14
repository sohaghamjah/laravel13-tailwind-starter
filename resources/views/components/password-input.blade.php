{{-- resources/views/components/password-input.blade.php --}}
@props([
    'disabled' => false,
    'id' => null,
    'name' => null,
    'placeholder' => 'Enter your password',
    'value' => null,
    'required' => false,
    'error' => false,
])

@php
    $errorClasses = $error
        ? 'border-error-500 focus:border-error-500 focus:ring-error-500/10 dark:border-error-500'
        : 'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:focus:border-brand-800';

    $requiredAttr = $required ? 'required' : '';
@endphp

<div x-data="{ showPassword: false }" class="relative">
    <input
        :type="showPassword ? 'text' : 'password'"
        @if($id) id="{{ $id }}" @endif
        @if($name) name="{{ $name }}" @endif
        placeholder="{{ $placeholder }}"
        value="{{ $value ?? old($name) }}"
        @disabled($disabled)
        {{ $requiredAttr }}
        {{ $attributes->merge(['class' => "dark:bg-dark-900 h-11 w-full rounded-lg border bg-transparent py-2.5 pl-4 pr-11 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:outline-hidden focus:ring-3 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {$errorClasses}"]) }}
    />

   <span
        @click="showPassword = !showPassword"
        class="absolute z-30 text-gray-500 -translate-y-1/2 cursor-pointer right-4 top-1/2 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors"
    >
        <!-- Eye Closed -->
        <i x-show="!showPassword" data-lucide="eye-off" class="w-5 h-5"></i>

        <!-- Eye Open -->
        <i x-show="showPassword" data-lucide="eye" class="w-5 h-5"></i>
    </span>
</div>
