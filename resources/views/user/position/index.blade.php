<x-layouts.app>
    <x-slot:title>Manajemen Jabatan</x-slot>

    <div class="px-8 py-6">

        {{-- TITLE & BUTTONS --}}
        <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
            <x-page-title>Manajemen Jabatan</x-page-title>
            <a href="{{ route('positions.create') }}"
                class="inline-flex items-center justify-center gap-2 bg-[#007a7a] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#005f5f] active:bg-[#004d4d] transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Jabatan
            </a>
        </div>

        {{-- ALERTS --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        {{-- FILTER BAR --}}
        <form method="GET" action="{{ route('positions.index') }}"
            class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="flex flex-wrap items-center gap-3 px-5 py-4 border-b border-gray-200">

                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <span>Tampilkan</span>
                    <select name="entries" onchange="this.form.submit()"
                        class="bg-gray-50 border border-gray-300 rounded-lg px-2.5 py-1.5 text-sm text-gray-700 outline-none focus:ring-2 focus:ring-[#007a7a]/40 focus:border-[#007a7a] transition">
                        <option value="5" {{ request('entries') == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('entries', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('entries') == 50 ? 'selected' : '' }}>50</option>
                    </select>
                    <span>data</span>
                </div>

                <div class="h-6 w-px bg-gray-200 hidden sm:block"></div>

                <div class="flex items-center gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Kode/Nama..."
                        class="bg-gray-50 border border-gray-300 rounded-lg px-3 py-1.5 text-sm text-gray-700 outline-none focus:ring-2 focus:ring-[#007a7a]/40 focus:border-[#007a7a] transition">
                    <button type="submit"
                        class="bg-[#007a7a] text-white px-4 py-1.5 rounded-lg text-sm font-semibold hover:bg-[#005f5f] transition">Cari</button>
                    <a href="{{ route('positions.index') }}"
                        class="bg-gray-100 text-gray-700 px-4 py-1.5 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">Reset</a>
                </div>
            </div>
        </form>

        {{-- TABLE --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse" style="min-width: 500px;">
                    <thead class="bg-[#007a7a] text-white">
                        <tr>
                            <th class="text-center w-16 py-3 px-4 font-semibold text-sm">No</th>
                            <th class="text-left py-3 px-4 font-semibold text-sm">Kode Jabatan</th>
                            <th class="text-left py-3 px-4 font-semibold text-sm">Nama Jabatan</th>
                            <th class="text-center w-48 py-3 px-4 font-semibold text-sm">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($positions as $key => $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="text-center py-3 px-4">{{ $positions->firstItem() + $key }}</td>
                                <td class="py-3 px-4">{{ $item->code }}</td>
                                <td class="py-3 px-4">{{ $item->name }}</td>
                                <td class="text-center py-3 px-4">
                                    <div class="flex justify-center gap-1.5">
                                        <a href="{{ route('positions.edit', $item->id) }}"
                                            class="inline-flex items-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-xs font-semibold transition">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('positions.destroy', $item->id) }}" method="POST"
                                            class="inline-block m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus jabatan ini?');"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs font-semibold transition">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-gray-500 py-6 px-4">
                                    Belum ada data jabatan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="px-5 py-4 border-t border-gray-200">
                {{ $positions->appends(request()->query())->links('components.pagination') }}
            </div>
        </div>
    </div>
</x-layouts.app>
