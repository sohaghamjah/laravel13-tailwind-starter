@props([
    'id' => null,
    'name' => null,
    'options' => [],
    'selected' => null,
    'placeholder' => 'Select Option',
    'valueField' => 'id',
    'labelField' => 'name',
    'disabled' => false,
])

<select
    @if($id) id="{{ $id }}" @endif
    @if($name) name="{{ $name }}" @endif
    @disabled($disabled)
    {{ $attributes->merge([
        'class' => 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 ' . ($errors->has($name) ? 'border-error-500' : '')
    ]) }}
>
    <option value="">{{ $placeholder }}</option>

    @foreach($options as $option)
        <option value="{{ $option->$valueField }}"
            {{ old($name, $selected) == $option->$valueField ? 'selected' : '' }}>
            {{ $option->$labelField }}
        </option>
    @endforeach
</select>
