<x-layouts.app>
    @section('title', 'Manajemen Akun')

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">MANAJEMEN AKUN</h1>
        <div class="flex gap-2">
            <a href="{{ route('accounts.import.view') }}"
                class="inline-flex items-center justify-center text-black px-5 py-2.5 rounded-full font-bold shadow transition hover:opacity-85"
                style="background-color:#D6DE20;">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12" />
                </svg>
                Import Akun
            </a>
            <a href="{{ route('accounts.create') }}"
                class="inline-flex items-center justify-center bg-[#1A5555] hover:opacity-85 text-white font-bold px-5 py-2.5 rounded-full shadow transition">
                <svg class="w-5 h-5 mr-2 inline-block -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Akun
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

    {{-- Card Tabel --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">

        <x-table-toolbar actionUrl="{{ route('accounts.index') }}" searchPlaceholder="Cari NIP, Nama, Email, Role..." />

        <x-table>
            <x-slot name="header">
                <tr>
                    <th class="px-4 py-3 text-center w-16">No.</th>
                    <th class="px-4 py-3">Nama Akun</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Scope Unit</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </x-slot>

            @forelse($users as $index => $u)
            <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                <td class="px-4 py-3 text-sm text-gray-500 text-center">{{ $users->firstItem() + $index }}</td>
                <td class="px-4 py-3 text-sm text-gray-900">{{ $u->name }}</td>
                <td class="px-4 py-3 text-sm text-gray-900">{{ $u->email }}</td>
                <td class="px-4 py-3 text-sm text-gray-900">{{ $u->roles->pluck('name')->join(', ') }}</td>
                <td class="px-4 py-3 text-sm text-gray-900">
                    @if($u->work_unit_id)
                        {{ $u->workUnit->name }}
                    @else
                        <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold">Semua Unit</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <div class="flex justify-center gap-2 items-center">
                        <a href="{{ route('accounts.edit', $u->id) }}"
                            class="inline-flex items-center px-3 py-1.5 bg-[#1A5555] text-white rounded text-sm font-medium transition hover:opacity-90">
                            Edit
                        </a>
                        <form action="{{ route('accounts.destroy', $u->id) }}" method="POST"
                            class="inline m-0"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?');">
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
                <td colspan="6" class="px-4 py-8 text-center text-gray-500">Belum ada data akun.</td>
            </tr>
            @endforelse
        </x-table>

        <x-table-footer :paginator="$users->appends(request()->query())" />
    </div>

</x-layouts.app>
