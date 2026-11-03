@props(['type' => 'info'])

@php
$classes = match ($type) {
    'success' => 'border-green-200 bg-green-50 text-green-800',
    'error' => 'border-red-200 bg-red-50 text-red-800',
    'warning' => 'border-amber-200 bg-amber-50 text-amber-900',
    default => 'border-sky-200 bg-sky-50 text-sky-900',
};
@endphp

<div role="{{ $type === 'error' ? 'alert' : 'status' }}" {{ $attributes->merge(['class' => "rounded-md border px-4 py-3 text-sm $classes"]) }}>
    {{ $slot }}
</div>
