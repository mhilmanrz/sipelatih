<x-layouts.app>
@section('title', 'Usulan Diklat')

    <!-- TITLE & BUTTON -->
    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        <x-page-title>Usulan Diklat</x-page-title>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('kegiatan.import.page') }}"
                class="inline-flex items-center justify-center gap-2 bg-white border border-[#007a7a] text-[#007a7a] px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#007a7a] hover:text-white transition"
                title="Import Usulan Kegiatan dari Excel" id="btnImport">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12"></path>
                </svg>
                Import Kegiatan
            </a>
            <a href="{{ route('kegiatan.import-per-peserta.page') }}"
                class="inline-flex items-center justify-center gap-2 bg-white border border-[#007a7a] text-[#007a7a] px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#007a7a] hover:text-white transition"
                title="Import Kegiatan beserta Peserta dari Excel" id="btnImportPeserta">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12"></path>
                </svg>
                Import per Peserta
            </a>
            <a href="{{ route('kegiatan.create') }}"
                class="inline-flex items-center justify-center gap-2 bg-[#007a7a] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#005f5f] active:bg-[#004d4d] transition shadow-sm"
                id="btnTambah">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Data
            </a>
        </div>
    </div>

    <!-- AREA TABLE -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">

        <!-- Table Control -->
        <form method="GET" action="{{ route('usulan-diklat') }}"
            class="flex flex-wrap justify-between items-center gap-4 bg-white rounded-t-xl px-5 py-4 border-b border-gray-200">

            <div class="flex items-center gap-2 text-sm text-gray-600">
                <span>Tampilkan</span>
                <select name="entries" onchange="this.form.submit()"
                    class="bg-gray-50 border border-gray-300 rounded-lg px-2.5 py-1.5 text-sm text-gray-700 outline-none focus:ring-2 focus:ring-[#007a7a]/40 focus:border-[#007a7a] transition">
                    <option value="5" {{ request('entries') == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('entries', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                </select>
                <span>data</span>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <select name="year" onchange="this.form.submit()"
                    class="bg-gray-50 border border-gray-300 rounded-lg px-3 py-1.5 text-sm text-gray-700 outline-none focus:ring-2 focus:ring-[#007a7a]/40 focus:border-[#007a7a] transition appearance-none pr-8 bg-[url('data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22%236b7280%22%3E%3Cpath%20fill-rule%3D%22evenodd%22%20d%3D%22M5.23%207.21a.75.75%200%20011.06.02L10%2011.168l3.71-3.938a.75.75%200%20111.08%201.04l-4.25%204.5a.75.75%200%2001-1.08%200l-4.25-4.5a.75.75%200%2001.02-1.06z%22%20clip-rule%3D%22evenodd%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem] bg-[right_0.5rem_center] bg-no-repeat">
                    <option value="">Semua Tahun</option>
                    @php
                        $currentYear = date('Y');
                    @endphp
                    @for ($y = $currentYear + 1; $y >= 2020; $y--)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                            {{ $y }}</option>
                    @endfor
                </select>

                <select name="status" onchange="this.form.submit()"
                    class="bg-gray-50 border border-gray-300 rounded-lg px-3 py-1.5 text-sm text-gray-700 outline-none focus:ring-2 focus:ring-[#007a7a]/40 focus:border-[#007a7a] transition appearance-none pr-8 bg-[url('data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22%236b7280%22%3E%3Cpath%20fill-rule%3D%22evenodd%22%20d%3D%22M5.23%207.21a.75.75%200%20011.06.02L10%2011.168l3.71-3.938a.75.75%200%20111.08%201.04l-4.25%204.5a.75.75%200%2001-1.08%200l-4.25-4.5a.75.75%200%2001.02-1.06z%22%20clip-rule%3D%22evenodd%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem] bg-[right_0.5rem_center] bg-no-repeat">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                    <option value="revision" {{ request('status') === 'revision' ? 'selected' : '' }}>Revision</option>
                    <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Accepted</option>
                </select>

                <div class="relative flex items-center">
                    <svg class="absolute left-3 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kegiatan..."
                        class="bg-gray-50 border border-gray-300 rounded-lg pl-9 pr-4 py-1.5 text-sm text-gray-700 outline-none focus:ring-2 focus:ring-[#007a7a]/40 focus:border-[#007a7a] transition w-48">
                    <button type="submit"
                        class="bg-[#007a7a] text-white px-4 py-1.5 rounded-lg hover:bg-[#006666] active:bg-[#005555] transition text-sm font-medium ml-2">
                        Cari
                    </button>
                </div>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse" id="monitorTable" style="min-width:900px;">
                <thead class="bg-[#007a7a] text-white text-xs">
                    <tr>
                        <th class="border-b border-gray-400 py-2 px-2 font-semibold text-center w-12">No</th>
                        <th class="border-b border-gray-400 py-2 px-2 font-semibold text-center w-16">Tahun</th>
                        <th class="border-b border-gray-400 py-2 px-2 font-semibold text-left">Unit Pengusul</th>
                        <th class="border-b border-gray-400 py-2 px-2 font-semibold text-left">Nama Kegiatan</th>
                        <th class="border-b border-gray-400 py-2 px-2 font-semibold text-left">Jenis</th>
                        <th class="border-b border-gray-400 py-2 px-2 font-semibold text-left">Pelaksanaan</th>
                        <th class="border-b border-gray-400 py-2 px-2 font-semibold text-center w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kegiatan as $key => $item)
                        <tr class="hover:bg-gray-50 transition text-sm">
                            <td class="border-b border-gray-300 py-2 px-2 text-center">
                                {{ $kegiatan->firstItem() + $key }}
                            </td>
                            <td class="border-b border-gray-300 py-2 px-2 text-center">
                                {{ $item->start_date ? \Carbon\Carbon::parse($item->start_date)->format('Y') : '-' }}
                            </td>
                            <td class="border-b border-gray-300 py-2 px-2 text-sm">{{ $item->workUnit->name ?? '-' }}</td>
                            <td class="border-b border-gray-300 py-2 px-2 text-sm">{{ $item->activityName->name ?? '-' }}</td>
                            <td class="border-b border-gray-300 py-2 px-2 text-sm">{{ $item->activityType->name ?? '-' }}</td>
                            <td class="border-b border-gray-300 py-2 px-2 text-sm">
                                @if ($item->start_date && $item->end_date)
                                    {{ \Carbon\Carbon::parse($item->start_date)->format('d-m-Y') }}
                                    s/d
                                    {{ \Carbon\Carbon::parse($item->end_date)->format('d-m-Y') }}
                                    @if ($item->start_time && $item->end_time)
                                        <br>{{ $item->start_time }} – {{ $item->end_time }}
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td class="border-b border-gray-300 py-2 px-2 text-center">
                                <div class="flex justify-center gap-1 items-center">
                                    <a href="{{ route('kegiatan.show', $item->id) }}"
                                        style="background-color: #3b82f6;"
                                        class="text-white px-2 py-1 rounded hover:bg-[#2563eb] text-xs font-semibold transition"
                                        title="Detail">
                                        Detail
                                    </a>
                                    <a href="{{ route('kegiatan.edit', $item->id) }}"
                                        style="background-color: #eab308;"
                                        class="text-white px-2 py-1 rounded hover:bg-[#ca8a04] text-xs font-semibold transition"
                                        title="Edit">
                                        Edit
                                    </a>
                                    <form action="{{ route('kegiatan.destroy', $item->id) }}" method="POST"
                                        class="inline m-0"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background-color: #ef4444;"
                                            class="text-white px-2 py-1 rounded hover:bg-[#dc2626] text-xs font-semibold transition"
                                            title="Hapus">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="border-b border-gray-300 py-4 text-center text-gray-500 text-sm">Tidak ada
                                data kegiatan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <div class="px-5 py-4 border-t border-gray-200">
            {{ $kegiatan->appends(request()->query())->links('components.pagination') }}
        </div>
    </div>

@push('scripts')
@endpush
</x-layouts.app>
