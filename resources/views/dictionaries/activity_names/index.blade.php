<x-layouts.app>
    <x-slot:title>Manajemen Nama Kegiatan</x-slot>

    <div class="px-8 py-6">

        {{-- TITLE & BUTTONS --}}
        <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
            <x-page-title>Manajemen Nama Kegiatan</x-page-title>
            <div class="flex flex-wrap gap-2">
                <button type="button" onclick="toggleModal('importModal')"
                    class="inline-flex items-center justify-center gap-2 bg-white border border-[#007a7a] text-[#007a7a] px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#007a7a] hover:text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12"></path>
                    </svg>
                    Import Excel
                </button>
                <a href="{{ route('activity-names.create') }}"
                    class="inline-flex items-center justify-center gap-2 bg-[#007a7a] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#005f5f] active:bg-[#004d4d] transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Nama Kegiatan
                </a>
            </div>
        </div>

        {{-- ALERTS --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        {{-- TABLE --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse" style="min-width: 700px;">
                    <thead class="bg-[#007a7a] text-white">
                        <tr>
                            <th class="text-center w-16 py-3 px-4 font-semibold text-sm">No.</th>
                            <th class="text-left py-3 px-4 font-semibold text-sm">Nama Kegiatan</th>
                            <th class="text-left py-3 px-4 font-semibold text-sm">Tgl Mulai</th>
                            <th class="text-left py-3 px-4 font-semibold text-sm">Tgl Selesai</th>
                            <th class="text-center py-3 px-4 font-semibold text-sm">Tahun</th>
                            <th class="text-center w-48 py-3 px-4 font-semibold text-sm">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($activityNames as $index => $activityName)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="text-center py-3 px-4">{{ $activityNames->firstItem() + $index }}</td>
                                <td class="font-medium py-3 px-4">{{ $activityName->name }}</td>
                                <td class="py-3 px-4">{{ $activityName->start_date }}</td>
                                <td class="py-3 px-4">{{ $activityName->end_date }}</td>
                                <td class="text-center py-3 px-4">{{ $activityName->year ?? '-' }}</td>
                                <td class="text-center py-3 px-4">
                                    <div class="flex justify-center gap-1.5">
                                        <a href="{{ route('activity-names.edit', $activityName->id) }}"
                                            class="inline-flex items-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-xs font-semibold transition">
                                            Edit
                                        </a>
                                        <form action="{{ route('activity-names.destroy', $activityName->id) }}" method="POST"
                                            class="inline-block m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus Nama Kegiatan ini?')"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs font-semibold transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-gray-500 py-6 px-4">
                                    Belum ada data Nama Kegiatan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="px-5 py-4 border-t border-gray-200">
                {{ $activityNames->appends(request()->query())->links('components.pagination') }}
            </div>
        </div>
    </div>

    {{-- IMPORT MODAL --}}
    <div id="importModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg mx-4 overflow-hidden">
            <form action="{{ route('activity-names.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="bg-[#007a7a] px-6 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-white">Import Nama Kegiatan</h2>
                    <button type="button" class="text-white hover:text-gray-200 focus:outline-none text-2xl" onclick="toggleModal('importModal')">&times;</button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-2">Unduh template Excel untuk format yang benar.</p>
                        <a href="{{ route('activity-names.template') }}" class="text-[#007a7a] hover:text-[#005f5f] text-sm font-semibold underline">Download Template Excel</a>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih File Excel <span class="text-red-500">*</span></label>
                        <input type="file" name="file" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-[#007a7a]/40 focus:border-[#007a7a] transition">
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 border-t border-gray-200">
                    <button type="button" onclick="toggleModal('importModal')"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium text-sm transition">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-[#007a7a] text-white rounded-lg hover:bg-[#005f5f] font-medium text-sm transition">Upload</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.toggle('hidden');
        }
    </script>
</x-layouts.app>
