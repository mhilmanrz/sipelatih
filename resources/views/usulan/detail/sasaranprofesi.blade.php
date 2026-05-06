<section style="margin-top: 2rem;">

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <strong class="font-bold">Terjadi kesalahan!</strong>
            <ul class="list-disc pl-5 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- TABLE CARD -->
    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 2rem;">
        <div style="display: flex; justify-content: flex-start; margin-bottom: 1rem;">
            <button id="openModal" class="bg-teal-500 hover:bg-teal-600 text-white font-semibold py-2 px-4 rounded shadow transition-colors" style="background-color: #14b8a6; color: white;">
                <svg class="w-5 h-5 mr-2 inline-block -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Sasaran Profesi
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse border border-gray-200">
                <thead class="bg-[#007a7a] border border-white py-3 px-4 font-semibold">
                    <tr>
                        <th class="-300 text-center w-16 border border-white py-3 px-4 font-semibold">NO.</th>
                        <th class="-300 text-left border border-white py-3 px-4 font-semibold">Profesi</th>
                        <th class="-300 text-center w-32 border border-white py-3 px-4 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody id="profesiTableBody" class="bg-white">
                    @forelse ($kegiatan->activityProfessions as $index => $item)
                        <tr>
                            <td class="-300 text-center border border-gray-200 py-3 px-4">{{ $index + 1 }}</td>
                            <td class="-300 border border-gray-200 py-3 px-4">{{ $item->profession->name ?? 'Profesi Tidak Ditemukan' }}</td>
                            <td class="-300 text-center border border-gray-200 py-3 px-4">
                                <form action="{{ route('kegiatan.sasaran-profesi.destroy', ['kegiatan' => $kegiatan->id, 'id' => $item->id]) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus profesi ini dari sasaran?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"   style="background-color: #ef4444;" class="text-white px-3 py-1.5 rounded hover:bg-[#dc2626] text-sm font-semibold transition inline-block">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="-300 text-center text-gray-500 border border-gray-200 py-3 px-4">Belum ada sasaran profesi yang ditambahkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- MODAL TAMBAH -->
<div id="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 50; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 8px; padding: 2rem; width: 500px; max-width: 90%;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.25rem; font-weight: bold; margin: 0;">Tambah Sasaran Profesi</h2>
            <button id="closeModal" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #6b7280;">✖</button>
        </div>

        <form action="{{ route('kegiatan.sasaran-profesi.store', $kegiatan->id) }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">*Sasaran Profesi</label>
                <select name="profession_id" id="professionSelect" required class="w-full border border-gray-300 rounded-md p-2 focus:ring-teal-500 focus:border-teal-500">
                    <option value="">- PILIH PROFESI -</option>
                    @foreach ($professions as $prof)
                        <option value="{{ $prof->id }}">{{ $prof->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
                <button type="button" id="cancelBtn" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded transition-colors" style="background-color: #d1d5db; color: #1f2937; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 px-4 rounded shadow transition-colors" style="background-color: #0d9488; color: white; cursor: pointer;">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('openModal')?.addEventListener('click', function() {
        document.getElementById('modal').style.display = 'flex';
    });
    document.getElementById('closeModal')?.addEventListener('click', function() {
        document.getElementById('modal').style.display = 'none';
    });
    document.getElementById('cancelBtn')?.addEventListener('click', function() {
        document.getElementById('modal').style.display = 'none';
    });
</script>