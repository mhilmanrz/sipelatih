@extends('layout.LayoutSuperAdmin')

@section('title', 'Usulan Diklat')

@push('styles')
<script src="https://cdn.tailwindcss.com"></script>
<style>
    .tw-wrap p,
    .tw-wrap h1,
    .tw-wrap h2,
    .tw-wrap h3,
    .tw-wrap h4,
    .tw-wrap h5,
    .tw-wrap h6,
    .tw-wrap span,
    .tw-wrap div,
    .tw-wrap a,
    .tw-wrap button,
    .tw-wrap table,
    .tw-wrap th,
    .tw-wrap td,
    .tw-wrap tr,
    .tw-wrap thead,
    .tw-wrap tbody {
        font-family: inherit;
    }
</style>
@endpush

@section('content')
<div class="tw-wrap p-6">

    {{-- Title & Buttons --}}
    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold text-white">USULAN DIKLAT</h1>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('kegiatan.import.page') }}"
                class="inline-flex items-center justify-center text-white px-5 py-2.5 rounded-full font-bold shadow transition hover:opacity-85"
                style="background-color:#205252;"
                title="Import Usulan Kegiatan dari Excel">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12"></path>
                </svg>
                Import Kegiatan
            </a>
            <a href="{{ route('kegiatan.import-per-peserta.page') }}"
                class="inline-flex items-center justify-center text-black px-5 py-2.5 rounded-full font-bold shadow transition hover:opacity-85"
                style="background-color:#D6DE20;"
                title="Import Kegiatan beserta Peserta dari Excel">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12"></path>
                </svg>
                Import per Peserta
            </a>
            <a href="{{ route('kegiatan.create') }}"
                class="inline-flex items-center justify-center bg-[#1A5555] hover:opacity-85 text-white font-bold px-5 py-2.5 rounded-full shadow transition">
                + Tambah Data
            </a>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">

        {{-- Table Control Bar --}}
        <form method="GET" action="{{ route('usulan-diklat') }}"
            class="flex flex-wrap justify-between items-center p-6 gap-4 text-white" style="background-color:#205252;">

            <div class="flex items-center gap-2">
                <span>Show</span>
                <select name="entries" onchange="this.form.submit()"
                    class="bg-transparent border border-white text-white rounded px-2 py-1 outline-none">
                    <option value="5" class="text-black" {{ request('entries') == 5  ? 'selected' : '' }}>5</option>
                    <option value="10" class="text-black" {{ request('entries', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" class="text-black" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                </select>
                <span>entries</span>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <select name="year" onchange="this.form.submit()"
                    class="bg-transparent border border-white text-white rounded-full px-3 py-1.5 outline-none text-sm">
                    <option value="" class="text-black">Semua Tahun</option>
                    @php $currentYear = date('Y'); @endphp
                    @for ($y = $currentYear + 1; $y >= 2020; $y--)
                    <option value="{{ $y }}" class="text-black" {{ request('year') == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                    @endfor
                </select>

                <select name="status" onchange="this.form.submit()"
                    class="bg-transparent border border-white text-white rounded-full px-3 py-1.5 outline-none text-sm">
                    <option value="" class="text-black">Semua Status</option>
                    <option value="draft" class="text-black" {{ request('status') === 'draft'     ? 'selected' : '' }}>Draft</option>
                    <option value="submitted" class="text-black" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                    <option value="revision" class="text-black" {{ request('status') === 'revision'  ? 'selected' : '' }}>Revision</option>
                    <option value="accepted" class="text-black" {{ request('status') === 'accepted'  ? 'selected' : '' }}>Accepted</option>
                </select>

                <div class="flex items-center gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
                        class="bg-transparent border border-white text-white placeholder-gray-300 rounded-full px-4 py-1.5 outline-none text-sm">
                    <button type="submit" style="background-color:#D6DE20; color:black;"
                        class="px-4 py-1.5 rounded-full font-bold hover:opacity-90 transition text-sm">Search</button>
                </div>
            </div>
        </form>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#007a7a] border border-white py-3 px-4 font-semibold">
                    <tr>
                        <th class="text-center w-12 border border-white py-3 px-4 font-semibold">No</th>
                        <th class="text-center border border-white py-3 px-4 font-semibold">Aksi</th>
                        <th class="text-left border border-white py-3 px-4 font-semibold">Judul Kegiatan</th>
                        <th class="text-left border border-white py-3 px-4 font-semibold">Pengusul</th>
                        <th class="text-center border border-white py-3 px-4 font-semibold">JPL</th>
                        <th class="text-left border border-white py-3 px-4 font-semibold">Jenis Kegiatan</th>
                        <th class="text-left border border-white py-3 px-4 font-semibold">Waktu</th>
                        <th class="text-left border border-white py-3 px-4 font-semibold">Materi</th>
                        <th class="text-center border border-white py-3 px-4 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($kegiatan as $key => $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="text-center border border-gray-200 py-3 px-4 text-sm text-gray-500">
                            {{ $kegiatan->firstItem() + $key }}
                        </td>

                        {{-- Aksi --}}
                        <td class="text-center border border-gray-200 py-3 px-4">
                            <div class="flex justify-center gap-2 items-center">
                                <a href="{{ route('kegiatan.show', $item->id) }}"
                                    class="text-white px-3 py-1.5 rounded text-sm font-semibold transition hover:opacity-85"
                                    style="background-color: #3b82f6;">
                                    Detail
                                </a>
                                <a href="{{ route('kegiatan.edit', $item->id) }}"
                                    class="text-white px-3 py-1.5 rounded text-sm font-semibold transition hover:opacity-85"
                                    style="background-color: #eab308;">
                                    Edit
                                </a>
                                <form action="{{ route('kegiatan.destroy', $item->id) }}" method="POST"
                                    class="inline m-0"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        style="background-color: #ef4444;"
                                        class="text-white px-3 py-1.5 rounded text-sm font-semibold transition hover:opacity-85 inline-block">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>

                        {{-- Data --}}
                        <td class="border border-gray-200 py-3 px-4 text-sm text-gray-900">{{ $item->activityName->name ?? '-' }}</td>
                        <td class="border border-gray-200 py-3 px-4 text-sm text-gray-900">{{ $item->workUnit->name ?? '-' }}</td>
                        <td class="text-center border border-gray-200 py-3 px-4 text-sm text-gray-900">-</td>
                        <td class="border border-gray-200 py-3 px-4 text-sm text-gray-900">{{ $item->activityType->name ?? '-' }}</td>
                        <td class="border border-gray-200 py-3 px-4 text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($item->start_date)->format('d-m-Y') }}<br>s/d<br>
                            {{ \Carbon\Carbon::parse($item->end_date)->format('d-m-Y') }}
                        </td>
                        <td class="border border-gray-200 py-3 px-4 text-sm text-gray-900">{{ $item->materialType->name ?? '-' }}</td>

                        {{-- Status Badge --}}
                        <td class="text-center border border-gray-200 py-3 px-4">
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
                            <span class="{{ $bgClass }} px-3 py-1 rounded-full text-xs font-semibold">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-gray-500 text-sm border border-gray-200 py-6 px-4">
                            Tidak ada data kegiatan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <x-table-footer :paginator="$kegiatan->appends(request()->query())" />
    </div>

</div>
@endsection

@push('scripts')
@endpush