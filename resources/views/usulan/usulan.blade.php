<x-layouts.app>
@section('title', 'Usulan Diklat')

    <!-- TITLE & BUTTON -->
    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        <x-page-title>Usulan Diklat</x-page-title>
        <div class="flex gap-2">
            <a href="{{ route('kegiatan.import.page') }}"
                class="inline-flex items-center justify-center text-black px-5 py-2.5 rounded-full font-bold shadow transition"
                style="background-color:#D6DE20;" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'"
                title="Import Usulan Kegiatan dari Excel" id="btnImport">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12"></path>
                </svg>
                Import Kegiatan
            </a>
            <a href="{{ route('kegiatan.import-per-peserta.page') }}"
                class="inline-flex items-center justify-center text-black px-5 py-2.5 rounded-full font-bold shadow transition"
                style="background-color:#D6DE20;" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'"
                title="Import Kegiatan beserta Peserta dari Excel" id="btnImportPeserta">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12"></path>
                </svg>
                Import Kegiatan per Peserta
            </a>
            <a href="{{ route('kegiatan.create') }}"
                class="inline-flex items-center justify-center text-black px-5 py-2.5 rounded-full font-bold shadow transition"
                style="background-color:#D6DE20;" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'"
                id="btnTambah">
                ➕ Tambah Data
            </a>
        </div>
    </div>

    <!-- AREA TABLE -->
    <div>

        <!-- Table Control -->
        <form method="GET" action="{{ route('usulan-diklat') }}"
            class="flex flex-wrap justify-between items-center p-6 border-b border-gray-200 gap-4">

            <div class="flex items-center gap-2 text-gray-700">
                <span>Show</span>
                <select name="entries" onchange="this.form.submit()"
                    class="border rounded px-2 py-1 outline-none focus:ring-1 focus:ring-[#007a7a]">
                    <option value="5" {{ request('entries') == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('entries', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                </select>
                <span>entries</span>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <select name="year" onchange="this.form.submit()"
                    class="border rounded-full px-3 py-1.5 outline-none focus:ring-1 focus:ring-[#007a7a] text-sm text-gray-700">
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
                    class="border rounded-full px-3 py-1.5 outline-none focus:ring-1 focus:ring-[#007a7a] text-sm text-gray-700">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted
                    </option>
                    <option value="revision" {{ request('status') === 'revision' ? 'selected' : '' }}>Revision</option>
                    <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Accepted</option>
                </select>

                <div class="flex items-center gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
                        class="border rounded-full px-4 py-1.5 outline-none focus:ring-1 focus:ring-[#007a7a] text-sm">
                    <button type="submit"
                        class="bg-[#007a7a] text-white px-4 py-1.5 rounded-full hover:bg-[#006bd6] transition text-sm">Search</button>
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
        <div class="mt-4 px-6 pb-6">
            {{ $kegiatan->appends(request()->query())->links('pagination::tailwind') }}
        </div>
    </div>

@push('scripts')
@endpush
</x-layouts.app>
