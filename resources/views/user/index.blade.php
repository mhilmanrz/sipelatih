<x-layouts.app>
    @section('title', 'Manajemen Pegawai')

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">MANAJEMEN PEGAWAI</h1>
        <div class="flex items-center gap-3">
            <a href="{{ route('users.import.view') }}"
                class="inline-flex items-center justify-center text-black px-5 py-2.5 rounded-full font-bold shadow transition hover:opacity-85"
                style="background-color:#D6DE20;">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12" />
                </svg>
                Import Peserta
            </a>
            <a href="{{ route('users.create') }}"
                class="inline-flex items-center justify-center bg-[#1A5555] hover:opacity-85 text-white font-bold px-5 py-2.5 rounded-full shadow transition">
                + Tambah
            </a>
        </div>
    </div>

    {{-- Flash Message --}}
    @if (session('success'))
    <div class="relative px-4 py-3 mb-4 text-green-700 bg-green-100 border border-green-400 rounded" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Card Tabel --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">

        <x-table-toolbar actionUrl="{{ route('users.index') }}" searchPlaceholder="Cari NIP, Nama, Unit Kerja, Jenis Tenaga..." />

        <x-table>
            <x-slot name="header">
                <tr>
                    <th class="px-4 py-3">No.</th>
                    <th class="px-4 py-3">NIP/NPS</th>
                    <th class="px-4 py-3">Nama Pegawai</th>
                    <th class="px-4 py-3">Unit Kerja</th>
                    <th class="px-4 py-3">Tenaga</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </x-slot>

            @forelse($users as $index => $u)
            <tr class="border-b border-gray-200 hover:bg-gray-50">
                <td class="px-4 py-3 text-sm text-gray-500">{{ $users->firstItem() + $index }}</td>
                <td class="px-4 py-3 text-sm text-gray-900">{{ $u->employee_id ?? '-' }}</td>
                <td class="px-4 py-3 text-sm">
                    <a href="{{ route('users.edit', $u->id) }}"
                        class="text-[#007A7F] font-bold hover:underline">{{ $u->name }}</a>
                </td>
                <td class="px-4 py-3 text-sm text-gray-900">{{ $u->workUnit->name ?? '-' }}</td>
                <td class="px-4 py-3 text-sm text-gray-900">{{ $u->profession->name ?? '-' }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('users.edit', $u->id) }}"
                            class="inline-flex items-center px-3 py-1.5 bg-[#1A5555] text-white rounded text-sm font-medium transition hover:opacity-90">
                            Edit
                        </a>
                        <form action="{{ route('users.destroy', $u->id) }}" method="POST"
                            class="inline m-0"
                            onsubmit="return confirm('Hapus pegawai ini?');">
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
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-8 text-center text-gray-500">Belum ada data pegawai.</td>
            </tr>
            @endforelse
        </x-table>

        <x-table-footer :paginator="$users->appends(request()->query())" />
    </div>

</x-layouts.app>
