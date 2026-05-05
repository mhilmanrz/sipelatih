@props(['label'])

<div class="flex items-baseline py-2 border-b border-gray-200/60 last:border-b-0">
    <span class="w-36 shrink-0 text-xs font-medium text-gray-500 uppercase tracking-wide">{{ $label }}</span>
    <span class="text-sm font-medium text-gray-900 min-w-0">{{ $slot }}</span>
</div>