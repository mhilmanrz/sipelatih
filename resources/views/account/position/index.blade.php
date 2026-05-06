<x-layouts.app>
    <div class="bg-[#13b9c6] min-h-screen font-sans pb-8">

        <!-- TITLE & BUTTON -->
        <section class="px-8 py-6 flex flex-wrap justify-between items-center gap-4">
            <h1 class="text-white text-3xl font-bold">Manajemen Jabatan</h1>
            <a href="{{ route('positions.create') }}"
                class="inline-flex items-center gap-2 bg-white text-[#007a7a] px-5 py-2.5 rounded-full font-bold shadow hover:bg-gray-50 transition"
                id="btnTambah">
                <svg class="w-5 h-5 mr-2 inline-block -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Jabatan
            </a>
        </section>

        @if (session('success'))
            <div class="mx-8 mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- AREA TABLE -->
        <section class="mx-8 bg-white rounded-[20px] overflow-hidden shadow">

            <!-- Table Control -->
            <form method="GET" action="{{ route('positions.index') }}"
                class="flex flex-wrap justify-end items-center p-6 border-b border-gray-200">
                <div class="flex items-center gap-2">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari Kode/Nama..."
                        class="border rounded-full px-4 py-1.5 outline-none focus:ring-1 focus:ring-[#007a7a]">
                    <button type="submit"
                        class="bg-[#007a7a] text-white px-4 py-1.5 rounded-full hover:bg-[#006bd6] transition">Search</button>
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse" id="monitorTable">
                    <thead class="bg-[#007a7a] text-white">
                        <tr>
                            <th class="border border-gray-400 py-3 px-4 font-semibold text-center w-16">No</th>
                            <th class="border border-gray-400 py-3 px-4 font-semibold text-center w-48">Aksi</th>
                            <th class="border border-gray-400 py-3 px-4 font-semibold text-left">Kode Jabatan</th>
                            <th class="border border-gray-400 py-3 px-4 font-semibold text-left">Nama Jabatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($positions as $key => $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="border border-gray-300 py-3 px-4 text-center">
                                    {{ $positions->firstItem() + $key }}</td>
                                <td class="border border-gray-300 py-3 px-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <!-- Edit Button -->
                                        <a href="{{ route('positions.edit', $item->id) }}"
                                            class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded hover:bg-yellow-200 text-sm font-medium transition"
                                            title="Edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <!-- Delete Button -->
                                        <form action="{{ route('positions.destroy', $item->id) }}" method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus jabatan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-100 text-red-700 px-3 py-1 rounded hover:bg-red-200 text-sm font-medium transition"
                                                title="Hapus">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                <td class="border border-gray-300 py-3 px-4">{{ $item->code }}</td>
                                <td class="border border-gray-300 py-3 px-4">{{ $item->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="border border-gray-300 py-4 text-center text-gray-500">Belum ada
                                    data jabatan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="mt-4 px-6 pb-6 flex justify-end">
                {{ $positions->appends(request()->query())->links('pagination::tailwind') }}
            </div>
        </section>

    </div>
</x-layouts.app>
