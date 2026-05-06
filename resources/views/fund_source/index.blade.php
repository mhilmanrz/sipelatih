<x-layouts.app>
    @section('title', 'Manajemen Sumber Dana')

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">MANAJEMEN SUMBER DANA</h1>
        <a href="{{ route('fund-sources.create') }}"
            class="inline-flex items-center justify-center bg-[#1A5555] hover:opacity-85 text-white font-bold px-5 py-2.5 rounded-full shadow transition">
            <svg class="w-5 h-5 mr-2 inline-block -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Sumber Dana
        </a>
    </div>

    {{-- Flash Message --}}
    @if (session('success'))
    <div class="relative px-4 py-3 mb-4 text-green-700 bg-green-100 border border-green-400 rounded" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Card Tabel --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">

        <x-table-toolbar actionUrl="{{ route('fund-sources.index') }}" searchPlaceholder="Cari sumber dana..." />

        <x-table>
            <x-slot name="header">
                <tr>
                    <th class="px-4 py-3">No.</th>
                    <th class="px-4 py-3">Nama Sumber Dana</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </x-slot>

            @forelse($fundSources as $index => $fs)
            <tr class="border-b border-gray-200 hover:bg-gray-50">
                <td class="px-4 py-3 text-sm text-gray-500">{{ $fundSources->firstItem() + $index }}</td>
                <td class="px-4 py-3 text-sm text-gray-900">{{ $fs->name }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('fund-sources.edit', $fs->id) }}"
                            class="inline-flex items-center px-3 py-1.5 bg-[#1A5555] text-white rounded text-sm font-medium transition hover:opacity-90">
                            Edit
                        </a>
                        <form action="{{ route('fund-sources.destroy', $fs->id) }}" method="POST"
                            class="inline m-0"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus sumber dana ini?');">
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
                <td colspan="3" class="px-4 py-8 text-center text-gray-500">Belum ada data sumber dana.</td>
            </tr>
            @endforelse
        </x-table>

        <x-table-footer :paginator="$fundSources->appends(request()->query())" />
    </div>

</x-layouts.app>
