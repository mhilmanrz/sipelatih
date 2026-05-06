<x-layouts.app>
    @section('title', 'Usulan Diklat')

    <!-- TITLE & BUTTON -->
    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        <x-page-title>Usulan Diklat</x-page-title>
        <div class="flex gap-2">
            <a href="{{ route('kegiatan.import.page') }}"
                class="inline-flex items-center justify-center text-black px-5 py-2.5 rounded-full font-bold shadow transition bg-[#D6DE20] hover:opacity-85"
                title="Import Usulan Kegiatan dari Excel" id="btnImport">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12"></path>
                </svg>
                Import Kegiatan
            </a>
            <a href="{{ route('kegiatan.import-per-peserta.page') }}"
                class="inline-flex items-center justify-center text-black px-5 py-2.5 rounded-full text-white font-bold shadow transition bg-[#1A5555] hover:opacity-85"
                title="Import Kegiatan beserta Peserta dari Excel" id="btnImportPeserta">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12"></path>
                </svg>
                Import Kegiatan per Peserta
            </a>
            <a href="{{ route('kegiatan.create') }}"
                class="inline-flex items-center justify-center text-black px-5 py-2.5 rounded-full font-bold shadow transition bg-[#ffffff] hover:opacity-85"
                id="btnTambah">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Data
            </a>
        </div>
    </div>

    {{-- Flash Message --}}
    @if (session('success'))
    <div class="relative px-4 py-3 mb-4 text-green-700 bg-green-100 border border-green-400 rounded" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if (session('error'))
    <div class="relative px-4 py-3 mb-4 text-red-700 bg-red-100 border border-red-400 rounded" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    <!-- AREA TABLE -->
    <div class="bg-white rounded-lg shadow overflow-hidden">

        <x-table-toolbar actionUrl="{{ route('usulan-diklat') }}" searchPlaceholder="Cari kegiatan...">
            {{-- Extra filters: Year & Status --}}
            <select name="year" onchange="this.form.submit()"
                class="px-3 py-1.5 text-sm bg-transparent border border-white rounded-full outline-none text-white">
                <option value="" class="text-black">Semua Tahun</option>
                @php $currentYear = date('Y'); @endphp
                @for ($y = $currentYear + 1; $y >= 2020; $y--)
                <option value="{{ $y }}" class="text-black" {{ request('year') == $y ? 'selected' : '' }}>
                    {{ $y }}
                </option>
                @endfor
            </select>

            <select name="status" onchange="this.form.submit()"
                class="px-3 py-1.5 text-sm bg-transparent border border-white rounded-full outline-none text-white">
                <option value="" class="text-black">Semua Status</option>
                <option value="draft" class="text-black" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="submitted" class="text-black" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                <option value="revision" class="text-black" {{ request('status') === 'revision' ? 'selected' : '' }}>Revision</option>
                <option value="accepted" class="text-black" {{ request('status') === 'accepted' ? 'selected' : '' }}>Accepted</option>
            </select>
        </x-table-toolbar>

        <x-table>
            <x-slot name="header">
                <tr>
                    <th class="px-4 py-3 text-center w-16">No</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                    <th class="px-4 py-3 text-left">Judul Kegiatan</th>
                    <th class="px-4 py-3 text-left">Pengusul</th>
                    <th class="px-4 py-3 text-center">JPL</th>
                    <th class="px-4 py-3 text-left">Jenis Kegiatan</th>
                    <th class="px-4 py-3 text-left">Waktu</th>
                    <th class="px-4 py-3 text-left">Materi</th>
                    <th class="px-4 py-3 text-center">Status</th>
                </tr>
            </x-slot>

            @forelse($kegiatan as $key => $item)
            <tr class="border-b border-gray-200 hover:bg-gray-50">
                <td class="px-4 py-3 text-sm text-gray-500 text-center">
                    {{ $kegiatan->firstItem() + $key }}
                </td>
                <td class="px-4 py-3 text-center">
                    <div class="flex justify-center gap-2 items-center">
                        <!-- Detail Button -->
                        <a href="{{ route('kegiatan.show', $item->id) }}"
                            class="inline-flex items-center px-3 py-1.5 bg-[#1A5555] text-white rounded text-sm font-medium transition hover:opacity-90"
                            title="Detail">
                            Detail
                        </a>
                        <!-- Edit Button -->
                        <a href="{{ route('kegiatan.edit', $item->id) }}"
                            class="inline-flex items-center px-3 py-1.5 bg-amber-500 text-white rounded text-sm font-medium transition hover:opacity-90"
                            title="Edit">
                            Edit
                        </a>
                        <!-- Delete Button -->
                        <form action="{{ route('kegiatan.destroy', $item->id) }}" method="POST"
                            class="inline m-0"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                style="background-color: #ef4444;"
                                class="text-white px-3 py-1.5 rounded text-sm font-semibold transition hover:opacity-85 inline-block"
                                title="Hapus">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
                <td class="px-4 py-3 text-sm text-gray-900">{{ $item->activityName->name ?? '-' }}</td>
                <td class="px-4 py-3 text-sm text-gray-900">{{ $item->workUnit->name ?? '-' }}</td>
                <td class="px-4 py-3 text-sm text-gray-500 text-center">-</td>
                <td class="px-4 py-3 text-sm text-gray-900">{{ $item->activityType->name ?? '-' }}</td>
                <td class="px-4 py-3 text-sm text-gray-900">
                    {{ \Carbon\Carbon::parse($item->start_date)->format('d-m-Y') }} <br>s/d<br>
                    {{ \Carbon\Carbon::parse($item->end_date)->format('d-m-Y') }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-900">{{ $item->materialType->name ?? '-' }}</td>
                <td class="px-4 py-3 text-center">
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
                    <span class="{{ $bgClass }} px-3 py-1 rounded-full text-xs font-semibold">{{ ucfirst($status) }}</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="px-4 py-8 text-center text-gray-500">Tidak ada data kegiatan.</td>
            </tr>
            @endforelse
        </x-table>

        <x-table-footer :paginator="$kegiatan->appends(request()->query())" />
    </div>

    @push('scripts')
    @endpush
</x-layouts.app>