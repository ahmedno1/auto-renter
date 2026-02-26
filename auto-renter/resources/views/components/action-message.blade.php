@props([
    'on',
])

<div
    x-data="{ shown: false, timeout: null }"
    x-init="@this.on('{{ $on }}', () => { clearTimeout(timeout); shown = true; window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'success', message: @js($slot->isEmpty() ? __('Saved.') : strip_tags(trim((string) $slot))) } })); timeout = setTimeout(() => { shown = false }, 2000); })"
    x-show.transition.out.opacity.duration.1500ms="shown"
    x-transition:leave.opacity.duration.1500ms
    style="display: none"
    {{ $attributes->merge(['class' => 'text-sm']) }}
>
    {{ $slot->isEmpty() ? __('Saved.') : $slot }}
</div>
