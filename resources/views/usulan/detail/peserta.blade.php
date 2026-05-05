<section>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- TOP BAR -->
    <div class="flex justify-between items-center mb-4">
        <div class="flex items-center gap-2 text-gray-500 text-sm">
            Show
            <select class="px-3 py-1.5 border border-gray-300 rounded text-sm outline-none bg-white cursor-pointer">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
            entries
        </div>

        <div class="flex gap-2">
            <a href="{{ route('kegiatan.peserta.import.page', $kegiatan->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-4 py-2 rounded-lg text-sm transition inline-block no-underline">
                ⬇ Import Peserta
            </a>

            <a href="{{ route('kegiatan.peserta.create', $kegiatan->id) }}" class="bg-[#007a7a] hover:bg-[#005f5f] text-white font-semibold px-4 py-2 rounded-lg text-sm transition text-center no-underline">
                ＋ Tambah
            </a>
        </div>
    </div>

    <!-- FILTER -->
    <div class="flex gap-2.5 mb-6 items-center">
        <form action="{{ request()->url() }}" method="GET" class="flex-1 max-w-md flex gap-2">
            <input type="hidden" name="tab" value="peserta">
            <input type="text" name="search_peserta" value="{{ request('search_peserta') }}" placeholder="Cari Nama/NIP Peserta..." class="w-full border border-gray-300 rounded-md p-2 focus:ring-teal-500 focus:border-teal-500">
            <button type="submit" class="bg-[#007a7a] hover:bg-[#005f5f] text-white font-semibold px-4 py-2 rounded-lg text-sm transition">Cari</button>
            @if(request('search_peserta'))
                <a href="{{ request()->url() }}?tab=peserta" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-4 py-2 rounded-lg text-sm transition no-underline">Reset</a>
            @endif
        </form>
    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto mb-6">
        <table class="w-full text-sm border-collapse border border-gray-200">
            <thead class="bg-[#007a7a] text-white">
                <tr>
                    <th class="text-center w-16 border border-white py-3 px-4 font-semibold text-sm">NO.</th>
                    <th class="text-left border border-white py-3 px-4 font-semibold text-sm">NIP/NPS</th>
                    <th class="text-left border border-white py-3 px-4 font-semibold text-sm">Nama Peserta</th>
                    <th class="text-left border border-white py-3 px-4 font-semibold text-sm">Unit Kerja</th>
                    <th class="text-left border border-white py-3 px-4 font-semibold text-sm">Sertifikat</th>
                    <th class="text-center border border-white py-3 px-4 font-semibold text-sm">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @forelse ($kegiatan->activityParticipants as $index => $participant)
                    <tr>
                        <td class="text-center border border-gray-200 py-3 px-4">{{ $index + 1 }}</td>
                        <td class="border border-gray-200 py-3 px-4">{{ $participant->user->nip ?? '-' }}</td>
                        <td class="border border-gray-200 py-3 px-4">{{ $participant->user->name ?? '-' }}</td>
                        <td class="border border-gray-200 py-3 px-4">{{ $participant->user->workUnit->name ?? '-' }}</td>
                        <td class="border border-gray-200 py-3 px-4">{{ $participant->certificate_number ?? '-' }}</td>
                        <td class="text-center border border-gray-200 py-3 px-4">
                            <div class="flex justify-center gap-2">
                                <button type="button" onclick="openModalSertifikat('{{ route('kegiatan.peserta.update_certificate', ['kegiatan' => $kegiatan->id, 'id' => $participant->id]) }}', '{{ $participant->certificate_number }}')" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition border-none cursor-pointer">EDIT</button>
                                <form action="{{ route('kegiatan.peserta.destroy', ['kegiatan' => $kegiatan->id, 'id' => $participant->id]) }}" method="POST" onsubmit="return confirm('Hapus peserta ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition border-none cursor-pointer">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-gray-500 border border-gray-200 py-3 px-4">Belum ada peserta yang didaftarkan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- FOOTER -->
    <div class="flex justify-between items-center text-sm text-gray-500 mb-6">
        <span>Total {{ $kegiatan->activityParticipants->count() }} peserta</span>
        <div class="flex gap-1">
            <!-- Temporary static pagination UI -->
            <button class="px-3 py-1 border border-gray-300 rounded text-gray-500 hover:bg-gray-100 disabled:opacity-50" disabled>Previous</button>
            <button class="px-3 py-1 border border-teal-500 bg-teal-500 text-white rounded">1</button>
            <button class="px-3 py-1 border border-gray-300 rounded text-gray-500 hover:bg-gray-100 disabled:opacity-50" disabled>Next</button>
        </div>
    </div>

    <!-- Modal Sertifikat -->
    <div id="modalSertifikat" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4 shadow-lg">
            <h3 class="text-lg font-semibold mb-4 text-gray-900">Edit Nomor Sertifikat</h3>
            <form id="formSertifikat" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Sertifikat</label>
                    <input type="text" name="certificate_number" id="inputSertifikat" class="w-full border border-gray-300 rounded-md p-2 outline-none" placeholder="Masukkan Nomor Sertifikat">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModalSertifikat()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-4 py-2 rounded-lg text-sm transition cursor-pointer border-none">Batal</button>
                    <button type="submit" class="bg-[#007a7a] hover:bg-[#005f5f] text-white font-semibold px-4 py-2 rounded-lg text-sm transition cursor-pointer border-none">Simpan</button>
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
