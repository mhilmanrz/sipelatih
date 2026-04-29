@props([
'actionUrl' => request()->url(),
'searchPlaceholder' => 'Cari...'
])

<form method="GET" action="{{ $actionUrl }}" class="flex flex-wrap items-center justify-between gap-4 p-6 text-white rounded-t-lg bg-[#205252]">
    <div class="flex items-center gap-2">
        <span class="text-sm">Show</span>
        <select name="per_page" onchange="this.form.submit()" class="px-2 py-1 text-sm bg-transparent border border-white rounded outline-none w-20">
            <option value="10" class="text-black" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
            <option value="25" class="text-black" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
            <option value="50" class="text-black" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
        </select>
        <span class="text-sm">entries</span>
    </div>

    <div class="flex items-center gap-4">
        {{ $slot }}

        <div class="flex items-center gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ $searchPlaceholder }}"
                class="px-4 py-1.5 text-sm bg-transparent border border-white rounded-full outline-none placeholder-gray-300 w-64">
            <button type="submit" class="px-4 py-1.5 text-sm font-bold text-black transition rounded-full bg-[#D6DE20] hover:opacity-90">
                Search
            </button>
        </div>
    </div>
</form>