@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-3 py-2 text-sm rounded-full bg-gray-200 text-gray-900'
            : 'inline-flex items-center px-3 py-2 text-sm rounded-full text-gray-500 hover:text-gray-700 hover:bg-gray-100';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
