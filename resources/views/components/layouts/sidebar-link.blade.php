@props(['href', 'icon', 'active' => false, 'isSubmenu' => false])

@php
    $classes = "flex items-center rounded-lg transition-all duration-200 group text-sm ";
    
    if ($isSubmenu) {
        $classes .= "pl-8 pr-3 py-2 ";
        $classes .= $active ? "text-white bg-white/10" : "text-gray-400 hover:text-white hover:bg-white/5";
    } else {
        $classes .= "px-3 py-2.5 ";
        $classes .= $active ? "bg-white/10 text-white font-medium shadow-sm ring-1 ring-white/5" : "text-gray-300 hover:bg-white/5 hover:text-white";
    }

    $iconClasses = "fa-solid {$icon} mr-2 ";
    if ($isSubmenu) {
        $iconClasses .= "w-5 text-center opacity-80";
    } else {
        $iconClasses .= "w-6 text-center ";
        $iconClasses .= $active ? "text-brand-accent" : "text-gray-400 group-hover:text-gray-200";
    }
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => trim($classes)]) }}>
    <i class="{{ trim($iconClasses) }}"></i>
    <span>{{ $slot }}</span>
</a>
