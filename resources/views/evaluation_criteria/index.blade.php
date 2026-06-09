<x-layouts.app>
    <x-slot:title>Manajemen Kriteria Evaluasi</x-slot>

    <div class="px-8 py-6">

        {{-- TITLE & BUTTONS --}}
        <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
            <x-page-title>Manajemen Kriteria Evaluasi</x-page-title>
            <a href="{{ route('evaluation-criteria.create') }}"
                class="inline-flex items-center justify-center gap-2 bg-[#007a7a] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#005f5f] active:bg-[#004d4d] transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Kriteria Evaluasi
            </a>
        </div>

        {{-- ALERTS --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        {{-- FILTERS --}}
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
            <form action="{{ route('evaluation-criteria.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="q" class="sr-only">Cari</label>
                    <input type="text" name="q" id="q" value="{{ request('q') }}" placeholder="Cari kode atau nama kriteria..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#007a7a] focus:border-transparent">
                </div>
                <div class="w-48">
                    <label for="evaluation_type" class="sr-only">Tingkat Evaluasi</label>
                    <select name="evaluation_type" id="evaluation_type" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#007a7a] focus:border-transparent">
                        <option value="">Semua Tingkat</option>
                        <option value="1" {{ request('evaluation_type') == '1' ? 'selected' : '' }}>Level 1</option>
                        <option value="3" {{ request('evaluation_type') == '3' ? 'selected' : '' }}>Level 3</option>
                    </select>
                </div>
                <button type="submit" 
                    class="px-4 py-2 bg-[#007a7a] hover:bg-[#005f5f] text-white text-sm font-semibold rounded-lg transition">
                    Filter
                </button>
                @if (request('q') || request('evaluation_type'))
                    <a href="{{ route('evaluation-criteria.index') }}" 
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-semibold rounded-lg transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- TABLE --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6 border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse" style="min-width: 600px;">
                    <thead class="bg-[#007a7a] text-white">
                        <tr>
                            <th class="text-center w-12 py-3 px-4 font-semibold text-sm">No.</th>
                            <th class="text-left w-32 py-3 px-4 font-semibold text-sm">Kode</th>
                            <th class="text-left py-3 px-4 font-semibold text-sm">Nama Kriteria</th>
                            <th class="text-center w-40 py-3 px-4 font-semibold text-sm">Tingkat Evaluasi</th>
                            <th class="text-left py-3 px-4 font-semibold text-sm">Kategori</th>
                            <th class="text-center w-32 py-3 px-4 font-semibold text-sm">Scope</th>
                            <th class="text-center w-40 py-3 px-4 font-semibold text-sm">Isian / Tipe</th>
                            <th class="text-center w-20 py-3 px-4 font-semibold text-sm">Order</th>
                            <th class="text-center w-48 py-3 px-4 font-semibold text-sm">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($criteria as $index => $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-3 px-4 text-center">{{ $criteria->firstItem() + $index }}</td>
                                <td class="py-3 px-4 font-mono text-gray-700 font-semibold">{{ $item->code }}</td>
                                <td class="py-3 px-4 font-medium text-gray-900">{{ $item->name }}</td>
                                <td class="py-3 px-4 text-center">
                                    @if ($item->evaluation_type == 1)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Evaluasi 1
                                        </span>
                                    @elseif ($item->evaluation_type == 2)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            Evaluasi 2
                                        </span>
                                    @elseif ($item->evaluation_type == 3)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            Evaluasi 3
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-700">
                                    {{ $item->category->name ?? '-' }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if ($item->form_type === 'speaker')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            Narasumber
                                        </span>
                                    @elseif ($item->form_type === 'activity')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Kegiatan
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if ($item->type === 'rating')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Rating
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            Isian
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center font-semibold text-gray-600">{{ $item->order }}</td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex justify-center gap-1.5">
                                        <a href="{{ route('evaluation-criteria.edit', $item->id) }}"
                                            class="inline-flex items-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-xs font-semibold transition">
                                            Edit
                                        </a>
                                        <form action="{{ route('evaluation-criteria.destroy', $item->id) }}" method="POST"
                                            class="inline-block m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs font-semibold transition"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus Kriteria Evaluasi ini?')">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-gray-500 py-8 px-4">
                                    Belum ada data Kriteria Evaluasi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-4 border-t border-gray-200">
                {{ $criteria->links('components.pagination') }}
            </div>
        </div>
    </div>
</x-layouts.app>
