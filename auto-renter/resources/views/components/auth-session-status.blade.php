@props([
    'status',
])

@if ($status)
    <div
        x-data
        x-init="window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'success', message: @js($status) } }))"
        class="hidden"
    ></div>
    <noscript>
        <div {{ $attributes->merge(['class' => 'font-medium text-sm text-green-600']) }}>
            {{ $status }}
        </div>
    </noscript>
@endif
