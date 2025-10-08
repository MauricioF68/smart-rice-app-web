@props(['estado'])

@php
    $colorClasses = [
        'activa' => 'bg-green-100 text-green-800',
        'en_negociacion' => 'bg-yellow-100 text-yellow-800',
        'acordada' => 'bg-blue-100 text-blue-800',
        'rechazada' => 'bg-red-100 text-red-800',
        'cancelada' => 'bg-gray-100 text-gray-800',
    ];

    $classes = $colorClasses[$estado] ?? 'bg-gray-100 text-gray-800';
@endphp

<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $classes }}">
    {{ str_replace('_', ' ', ucfirst($estado)) }}
</span>