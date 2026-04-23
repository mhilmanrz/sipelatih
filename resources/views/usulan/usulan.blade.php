@extends('layout.LayoutSuperAdmin')

@section('title', 'Usulan Diklat')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/LayoutSuperAdmin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/usulan.css') }}">
@endpush

@section('content')
    <div class="bg-[#13b9c6] min-h-screen font-sans pb-8">

        <!-- TITLE & BUTTON -->
        <section class="px-8 py-6 flex flex-wrap justify-between items-center gap-4">
            <x-page-title>Usulan Diklat</x-page-title>
            <div class="flex gap-2">
                <a href="{{ route('kegiatan.import.page') }}"
                    class="inline-flex items-center justify-center bg-gray-800 text-white px-5 py-2.5 rounded-full font-bold shadow hover:bg-gray-900 transition"
                    title="Import Usulan Kegiatan dari Excel" id="btnImport">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12"></path>
                    </svg>
                    Import Kegiatan
                </a>
                <a href="{{ route('kegiatan.import-per-peserta.page') }}"
                    class="inline-flex items-center justify-center bg-blue-800 text-white px-5 py-2.5 rounded-full font-bold shadow hover:bg-blue-900 transition"
                    title="Import Kegiatan beserta Peserta dari Excel" id="btnImportPeserta">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12"></path>
                    </svg>
                    Import Kegiatan per Peserta
                </a>
                <a href="{{ route('kegiatan.create') }}"
                    class="inline-flex items-center justify-center bg-white text-[#007a7a] px-5 py-2.5 rounded-full font-bold shadow hover:bg-gray-50 transition"
                    id="btnTambah">
                    ➕ Tambah Data
                </a>
            </div>
        </section>

        <!-- CHART AREA -->

        <!-- AREA TABLE -->
        <section class="mx-8 bg-white rounded-[20px] overflow-hidden shadow">

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
                <table class="w-full border-collapse" id="monitorTable">
                    <thead class="bg-[#007a7a] text-white">
                        <tr>
                            <th class="border border-gray-400 py-3 px-4 font-semibold text-center w-16">No</th>
                            <th class="border border-gray-400 py-3 px-4 font-semibold text-center">Aksi</th>
                            <th class="border border-gray-400 py-3 px-4 font-semibold text-left">Judul Kegiatan</th>
                            <th class="border border-gray-400 py-3 px-4 font-semibold text-left">Pengusul</th>
                            <th class="border border-gray-400 py-3 px-4 font-semibold text-center">JPL</th>
                            <th class="border border-gray-400 py-3 px-4 font-semibold text-left">Jenis Kegiatan</th>
                            <th class="border border-gray-400 py-3 px-4 font-semibold text-left">Waktu</th>
                            <th class="border border-gray-400 py-3 px-4 font-semibold text-left">Materi</th>
                            <th class="border border-gray-400 py-3 px-4 font-semibold text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kegiatan as $key => $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="border border-gray-300 py-3 px-4 text-center">
                                    {{ $kegiatan->firstItem() + $key }}
                                </td>
                                <td class="border border-gray-300 py-3 px-4 text-center">
                                    <div class="flex justify-center gap-2 items-center">
                                        <!-- Detail Button -->
                                        <a href="{{ route('kegiatan.show', $item->id) }}"
                                            style="background-color: #3b82f6;"
                                            class="text-white px-3 py-1.5 rounded hover:bg-[#2563eb] text-sm font-semibold transition"
                                            title="Detail">
                                            Detail
                                        </a>
                                        <!-- Edit Button -->
                                        <a href="{{ route('kegiatan.edit', $item->id) }}"
                                            style="background-color: #eab308;"
                                            class="text-white px-3 py-1.5 rounded hover:bg-[#ca8a04] text-sm font-semibold transition"
                                            title="Edit">
                                            Edit
                                        </a>
                                        <!-- Delete Button -->
                                        <form action="{{ route('kegiatan.destroy', $item->id) }}" method="POST"
                                            class="inline m-0"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background-color: #ef4444;"
                                                class="text-white px-3 py-1.5 rounded hover:bg-[#dc2626] text-sm font-semibold transition"
                                                title="Hapus">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                <td class="border border-gray-300 py-3 px-4">{{ $item->activityName->name ?? '-' }}</td>
                                <td class="border border-gray-300 py-3 px-4">{{ $item->workUnit->name ?? '-' }}</td>
                                <td class="border border-gray-300 py-3 px-4 text-center">-</td>
                                <td class="border border-gray-300 py-3 px-4">{{ $item->activityType->name ?? '-' }}</td>
                                <td class="border border-gray-300 py-3 px-4">
                                    {{ \Carbon\Carbon::parse($item->start_date)->format('d-m-Y') }} <br>s/d<br>
                                    {{ \Carbon\Carbon::parse($item->end_date)->format('d-m-Y') }}
                                </td>
                                <td class="border border-gray-300 py-3 px-4">{{ $item->materialType->name ?? '-' }}</td>
                                <td class="border border-gray-300 py-3 px-4 text-center">
                                    @php
                                        $status = $item->latestStatus->status ?? 'draft';
                                        $bgClass = match ($status) {
                                            'draft' => 'bg-gray-200 text-gray-700',
                                            'submitted' => 'bg-blue-100 text-blue-700',
                                            'revision' => 'bg-yellow-100 text-yellow-700',
                                            'accepted' => 'bg-green-100 text-green-700',
                                            default => 'bg-gray-200 text-gray-700',
                                        };
                                    @endphp
                                    <span
                                        class="{{ $bgClass }} px-3 py-1 rounded-full text-xs font-semibold">{{ ucfirst($status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="border border-gray-300 py-4 text-center text-gray-500">Tidak ada
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
        </section>

    </div>
@endsection

@push('scripts')
@endpush
