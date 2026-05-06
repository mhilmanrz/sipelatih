<section style="margin-top: 2rem;">

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- TOP BAR -->
    <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; align-items: center;">
        <div style="display: flex; align-items: center; gap: 8px; color: #555; font-size: 14px;">
            Show
            <select
                style="padding: 6px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; outline: none; background-color: white; cursor: pointer;">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
            entries
        </div>

        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('kegiatan.peserta.import.page', $kegiatan->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded shadow transition-colors" style="text-decoration: none; display: inline-block;">
                ⬇ Import Peserta
            </a>

            <a href="{{ route('kegiatan.peserta.create', $kegiatan->id) }}" class="hover:bg-teal-600 text-white font-semibold py-2 px-4 rounded shadow transition-colors text-center" style="background-color: #14b8a6; color: white; display: inline-block; text-decoration: none;">
                <svg class="w-5 h-5 mr-2 inline-block -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah
            </a>
        </div>
    </div>

    <!-- CARD -->
    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 2rem;">

        <!-- FILTER -->
        <div style="display: flex; gap: 10px; margin-bottom: 1.5rem; align-items: center;">
            <form action="{{ request()->url() }}" method="GET" style="flex-grow: 1; max-width: 400px; display: flex; gap: 0.5rem;">
                <input type="hidden" name="tab" value="peserta">
                <input type="text" name="search_peserta" value="{{ request('search_peserta') }}" placeholder="Cari Nama/NIP Peserta..." class="w-full border border-gray-300 rounded-md p-2 focus:ring-teal-500 focus:border-teal-500">
                <button type="submit" class="bg-teal-500 hover:bg-teal-600 text-white font-semibold py-2 px-4 rounded shadow transition-colors" style="background-color: #14b8a6; color: white;">Cari</button>
                @if(request('search_peserta'))
                    <a href="{{ request()->url() }}?tab=peserta" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded shadow transition-colors text-decoration-none" style="display: inline-flex; align-items: center;">Reset</a>
                @endif
            </form>
        </div>

        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse border border-gray-200">
                <thead class="bg-[#007a7a] border border-white py-3 px-4 font-semibold">
                    <tr>
                        <th class="-300 text-center w-16 border border-white py-3 px-4 font-semibold">NO.</th>
                        <th class="-300 text-left border border-white py-3 px-4 font-semibold">NIP/NPS</th>
                        <th class="-300 text-left border border-white py-3 px-4 font-semibold">Nama Peserta</th>
                        <th class="-300 text-left border border-white py-3 px-4 font-semibold">Unit Kerja</th>
                        <th class="-300 text-left border border-white py-3 px-4 font-semibold">Sertifikat</th>
                        <th class="-300 text-center border border-white py-3 px-4 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse ($kegiatan->activityParticipants as $index => $participant)
                        <tr>
                            <td class="-300 text-center border border-gray-200 py-3 px-4">{{ $index + 1 }}</td>
                            <td class="-300 border border-gray-200 py-3 px-4">{{ $participant->user->nip ?? '-' }}</td>
                            <td class="-300 border border-gray-200 py-3 px-4">{{ $participant->user->name ?? '-' }}</td>
                            <td class="-300 border border-gray-200 py-3 px-4">{{ $participant->user->workUnit->name ?? '-' }}</td>
                            <td class="-300 border border-gray-200 py-3 px-4">{{ $participant->certificate_number ?? '-' }}</td>
                            <td class="-300 text-center border border-gray-200 py-3 px-4">
                                <div style="display: flex; justify-content: center; gap: 0.5rem;">
                                    <button type="button" onclick="openModalSertifikat('{{ route('kegiatan.peserta.update_certificate', ['kegiatan' => $kegiatan->id, 'id' => $participant->id]) }}', '{{ $participant->certificate_number }}')" class="hover:bg-blue-600 text-white px-3 py-1 rounded text-xs transition-colors" style="background-color: #3b82f6; color: white; border: none; cursor: pointer;">EDIT</button>
                                    <form action="{{ route('kegiatan.peserta.destroy', ['kegiatan' => $kegiatan->id, 'id' => $participant->id]) }}" method="POST" onsubmit="return confirm('Hapus peserta ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"   style="background-color: #ef4444;" class="text-white px-3 py-1.5 rounded hover:bg-[#dc2626] text-sm font-semibold transition inline-block">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="-300 text-center text-gray-500 border border-gray-200 py-3 px-4">Belum ada peserta yang didaftarkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- FOOTER -->
        <div style="display: flex; justify-content: space-between; margin-top: 1.5rem; align-items: center; font-size: 0.875rem; color: #6b7280;">
            <span>Total {{ $kegiatan->activityParticipants->count() }} peserta</span>
            <div style="display: flex; gap: 0.25rem;">
                <!-- Temporary static pagination UI -->
                <button class="px-3 py-1 border border-gray-300 rounded text-gray-500 hover:bg-gray-100 disabled:opacity-50" disabled>Previous</button>
                <button class="px-3 py-1 border border-teal-500 bg-teal-500 text-white rounded">1</button>
                <button class="px-3 py-1 border border-gray-300 rounded text-gray-500 hover:bg-gray-100 disabled:opacity-50" disabled>Next</button>
            </div>
        </div>

    </div>

    <!-- Modal Sertifikat -->
    <div id="modalSertifikat" style="display: none; position: fixed; inset: 0; z-index: 50; align-items: center; justify-content: center; background-color: rgba(0, 0, 0, 0.5);">
        <div style="background-color: white; border-radius: 8px; padding: 1.5rem; width: 100%; max-width: 28rem; margin: 1rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem; color: #111827;">Edit Nomor Sertifikat</h3>
            <form id="formSertifikat" method="POST">
                @csrf
                @method('PUT')
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">Nomor Sertifikat</label>
                    <input type="text" name="certificate_number" id="inputSertifikat" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem; outline: none;" placeholder="Masukkan Nomor Sertifikat">
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                    <button type="button" onclick="closeModalSertifikat()" style="padding: 0.5rem 1rem; background-color: #e5e7eb; color: #1f2937; border-radius: 0.375rem; cursor: pointer; border: none; font-size: 0.875rem; font-weight: 500;">Batal</button>
                    <button type="submit" style="padding: 0.5rem 1rem; background-color: #14b8a6; color: white; border-radius: 0.375rem; cursor: pointer; border: none; font-size: 0.875rem; font-weight: 500;">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModalSertifikat(url, number) {
            document.getElementById('formSertifikat').action = url;
            document.getElementById('inputSertifikat').value = number || '';
            document.getElementById('modalSertifikat').style.display = 'flex';
        }

        function closeModalSertifikat() {
            document.getElementById('modalSertifikat').style.display = 'none';
        }
    </script>
</section>