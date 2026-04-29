<div class="overflow-x-auto w-full">
    <table class="w-full text-left border-collapse">
        @if (isset($header))
        <thead class="font-semibold text-white bg-[#007A7F] border border-white">
            {{ $header }}
        </thead>
        @endif
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>