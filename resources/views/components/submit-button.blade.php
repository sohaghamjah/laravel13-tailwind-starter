@props([
    'text' => 'Submit',
    'icon' => 'save',
    'loadingText' => 'Processing...',
])

<button 
    type="submit"
    x-data="{ loading: false }"
    @click="loading = true"
    {{ $attributes->merge([
        'class' => 'inline-flex items-center justify-center px-5 py-2 text-sm font-medium text-white transition bg-brand-500 rounded-lg shadow-theme-xs hover:bg-brand-600'
    ]) }}
>
    <!-- Icon -->
    <i x-show="!loading" data-lucide="{{ $icon }}" class="w-4 h-4 mr-2"></i>

    <!-- Text -->
    <span x-text="loading ? '{{ $loadingText }}' : '{{ $text }}'"></span>
</button>
