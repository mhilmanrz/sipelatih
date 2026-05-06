<div class="overflow-x-auto w-full">
    <table class="w-full text-left border-collapse">
        @if (isset($header))
        <thead class="font-semibold text-white bg-[#007a7a] border border-white">
            {{ $header }}
        </thead>
        @endif
        <tbody class="bg-white divide-y divide-gray-200">
            {{ $slot }}
        </tbody>
    </table>
</div>