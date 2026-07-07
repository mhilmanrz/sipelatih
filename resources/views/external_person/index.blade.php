<x-layouts.app>
    <x-slot:title>Narasumber/Moderator Eksternal</x-slot:title>

    <div class="px-8 py-6">

        <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
            <x-page-title>Narasumber/Moderator Eksternal</x-page-title>
            <a href="{{ route('external-persons.create') }}"
                class="inline-flex items-center justify-center gap-2 bg-[#007a7a] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#005f5f] active:bg-[#004d4d] transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <form method="GET" action="{{ route('external-persons.index') }}" class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="flex flex-wrap items-center gap-3 px-5 py-4 border-b border-gray-200">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Nama atau Instansi..."
                    class="bg-gray-50 border border-gray-300 rounded-lg px-3 py-1.5 text-sm text-gray-700 outline-none focus:ring-2 focus:ring-[#007a7a]/40 focus:border-[#007a7a] transition">
                <button type="submit" class="bg-[#007a7a] text-white px-4 py-1.5 rounded-lg text-sm font-semibold hover:bg-[#005f5f] transition">Cari</button>
                <a href="{{ route('external-persons.index') }}" class="bg-gray-100 text-gray-700 px-4 py-1.5 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">Reset</a>
            </div>
        </form>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse" style="min-width: 700px;">
                    <thead class="bg-[#007a7a] text-white">
                        <tr>
                            <th class="text-center w-12 py-3 px-4 font-semibold text-sm">No.</th>
                            <th class="text-left py-3 px-4 font-semibold text-sm">Nama</th>
                            <th class="text-left py-3 px-4 font-semibold text-sm">Instansi Asal</th>
                            <th class="text-left py-3 px-4 font-semibold text-sm">Jabatan Asal</th>
                            <th class="text-left py-3 px-4 font-semibold text-sm">Kapasitas</th>
                            <th class="text-center w-48 py-3 px-4 font-semibold text-sm">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($externalPersons as $index => $person)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="text-center py-3 px-4">{{ $externalPersons->firstItem() + $index }}</td>
                                <td class="py-3 px-4">
                                    <a href="{{ route('external-persons.edit', $person->id) }}" class="text-[#007a7a] hover:underline font-medium">{{ $person->name }}</a>
                                </td>
                                <td class="py-3 px-4">{{ $person->institution ?? '-' }}</td>
                                <td class="py-3 px-4">{{ $person->external_position ?? '-' }}</td>
                                <td class="py-3 px-4">{{ $person->roles->pluck('name')->map(fn ($r) => ucfirst($r))->implode(', ') ?: '-' }}</td>
                                <td class="text-center py-3 px-4">
                                    <div class="flex justify-center gap-1.5">
                                        <a href="{{ route('external-persons.edit', $person->id) }}"
                                            class="inline-flex items-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-xs font-semibold transition">
                                            Edit
                                        </a>
                                        <form action="{{ route('external-persons.destroy', $person->id) }}" method="POST" class="inline-block m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Hapus data ini?')"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs font-semibold transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-gray-500 py-6 px-4">
                                    Belum ada data narasumber/moderator eksternal.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-4 border-t border-gray-200">
                {{ $externalPersons->links('components.pagination') }}
            </div>
        </div>
    </div>
</x-layouts.app>
