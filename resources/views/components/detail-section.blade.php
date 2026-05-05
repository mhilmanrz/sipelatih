@props(['title', 'icon' => null])

<div class="bg-gray-50 rounded-xl mb-6 last:mb-0 overflow-hidden">
    @if($title)
        <div class="bg-[#007a7a]/10 border-b border-[#007a7a]/20 px-5 py-3">
            <h4 class="text-sm font-semibold text-[#007a7a] uppercase tracking-wider flex items-center gap-2">
                @if($icon)
                    <i class="fa {{ $icon }} text-xs"></i>
                @endif
                {{ $title }}
            </h4>
        </div>
    @endif
    <div class="px-5 py-4">
        {{ $slot }}
    </div>
</div>