<x-layouts.app>
    @section('title', 'Manajemen Unit Kerja')

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">MANAJEMEN UNIT KERJA</h1>
        <a href="{{ route('work-units.create') }}"
            class="inline-flex items-center justify-center bg-[#1A5555] hover:opacity-85 text-white font-bold px-5 py-2.5 rounded-full shadow transition">
            + Tambah Unit Kerja
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

        <x-table-toolbar actionUrl="{{ route('work-units.index') }}" searchPlaceholder="Cari unit kerja..." />

        <x-table>
            <x-slot name="header">
                <tr>
                    <th class="px-4 py-3">No.</th>
                    <th class="px-4 py-3">Nama Unit Kerja</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </x-slot>

            @forelse($workUnits as $index => $workUnit)
            <tr class="border-b border-gray-200 hover:bg-gray-50">
                <td class="px-4 py-3 text-sm text-gray-500">{{ $workUnits->firstItem() + $index }}</td>
                <td class="px-4 py-3 text-sm text-gray-900">{{ $workUnit->name }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('work-units.edit', $workUnit->id) }}"
                            class="inline-flex items-center px-3 py-1.5 bg-[#1A5555] text-white rounded text-sm font-medium transition hover:opacity-90">
                            Edit
                        </a>
                        <form action="{{ route('work-units.destroy', $workUnit->id) }}" method="POST"
                            class="inline m-0"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus Unit Kerja ini?');">
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
                <td colspan="3" class="px-4 py-8 text-center text-gray-500">Belum ada data Unit Kerja.</td>
            </tr>
            @endforelse
        </x-table>

        <x-table-footer :paginator="$workUnits->appends(request()->query())" />
    </div>

</x-layouts.app>
