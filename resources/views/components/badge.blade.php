@props(['text' => '', 'color' => 'blue', 'size' => 'md'])

@php
    $colorClasses = [
        'blue' => 'bg-blue-100 text-blue-800',
        'green' => 'bg-green-100 text-green-800',
        'red' => 'bg-red-100 text-red-800',
        'yellow' => 'bg-yellow-100 text-yellow-800',
        'purple' => 'bg-purple-100 text-purple-800',
        'gray' => 'bg-gray-100 text-gray-800',
        'indigo' => 'bg-indigo-100 text-indigo-800',
    ];

    $sizeClasses = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2.5 py-0.5 text-sm',
        'lg' => 'px-3 py-1 text-sm',
    ];

    $badgeColor = $colorClasses[$color] ?? $colorClasses['blue'];
    $badgeSize = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<span class="inline-flex items-center rounded-full font-medium {{ $badgeColor }} {{ $badgeSize }}">
    {{ $text }}
</span>

<!-- Badge Variants Examples -->

<!-- Status Badges -->
@if (!isset($text))
    <!-- Active Status -->
    <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
        <i class="ph ph-check-circle mr-1 text-xs"></i>
        Active
    </span>

    <!-- Inactive Status -->
    <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
        <i class="ph ph-x-circle mr-1 text-xs"></i>
        Inactive
    </span>

    <!-- Pending Status -->
    <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">
        <i class="ph ph-clock mr-1 text-xs"></i>
        Pending
    </span>

    <!-- Processing Status -->
    <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
        <i class="ph ph-spinner mr-1 text-xs animate-spin"></i>
        Processing
    </span>
@endif