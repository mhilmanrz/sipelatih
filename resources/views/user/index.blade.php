<x-layouts.app>
    @section('title', 'Manajemen Pegawai')

    @push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/manajemenPegawai.css') }}">
    @endpush


    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">DATA PENGGUNA</h1>
        <div class="flex items-center gap-3">
            <a href="{{ route('users.import.view') }}" class="inline-flex items-center justify-center px-5 py-2.5 font-bold text-black transition rounded-full shadow bg-[#D6DE20] hover:opacity-85">
                <i class="fa-solid fa-file-import mr-1"></i> Import Peserta
            </a>
            <a href="{{ route('users.create') }}"
                class="inline-flex items-center justify-center bg-[#1A5555] hover:opacity-85 text-white font-bold px-5 py-2.5 rounded-full shadow transition">
                + Tambah Pengguna
            </a>
        </div>
    </div>

    @if (session('success'))
    <div class="relative px-4 py-3 mb-4 text-green-700 bg-green-100 border border-green-400 rounded" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <!-- CARD -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <x-table-toolbar actionUrl="{{ route('users.index') }}" searchPlaceholder="Cari NIP, Nama, Unit Kerja...">
        </x-table-toolbar>

        <x-table>
            <x-slot name="header">
                <tr>
                    <th class="px-4 py-3">NO.</th>
                    <th class="px-4 py-3">NIP/NPS</th>
                    <th class="px-4 py-3">Nama Pegawai</th>
                    <th class="px-4 py-3">Unit Kerja</th>
                    <th class="px-4 py-3">Tenaga</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </x-slot>

            @forelse($users as $index => $u)
            <tr class="border-b border-gray-200">
                <td class="px-4 py-3">{{ $users->firstItem() + $index }}</td>
                <td class="px-4 py-3">{{ $u->employee_id ?? '-' }}</td>
                <td class="px-4 py-3"><a href="{{ route('users.edit', $u->id) }}" class="font-bold text-[#007A7F] hover:underline">{{ $u->name }}</a></td>
                <td class="px-4 py-3">{{ $u->workUnit->name ?? '-' }}</td>
                <td class="px-4 py-3">{{ $u->profession->name ?? '-' }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('users.edit', $u->id) }}" class="px-3 py-1.5 text-sm text-white transition rounded bg-[#007A7F] hover:bg-opacity-90">Edit</a>
                        <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="m-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 text-sm text-white transition bg-red-600 rounded hover:bg-red-700" onclick="return confirm('Hapus pegawai ini?')">Hapus</button>
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

        <x-table-footer :paginator="$users" />

        @push('scripts')
        <script src="{{ asset('assets/js/manajemenPegawai.js') }}"></script>
        @endpush

</x-layouts.app>