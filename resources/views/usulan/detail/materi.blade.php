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
                + Tambah Materi
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-gray-300 px-4 py-2 text-center text-gray-700 w-16">NO.</th>
                        <th class="border border-gray-300 px-4 py-2 text-left text-gray-700">Materi</th>
                        <th class="border border-gray-300 px-4 py-2 text-center text-gray-700 w-24">JPL</th>
                        <th class="border border-gray-300 px-4 py-2 text-center text-gray-700 w-24">JPL / 45 Menit</th>
                        <th class="border border-gray-300 px-4 py-2 text-center text-gray-700 w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody id="materiTableBody" class="bg-white">
                    @forelse ($kegiatan->activityMaterials as $index => $item)
                        <tr>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $index + 1 }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $item->name }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $item->value }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ round($item->value / 45, 2) }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <form action="{{ route('kegiatan.materi.destroy', ['kegiatan' => $kegiatan->id, 'id' => $item->id]) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs transition-colors" style="background-color: #ef4444; color: white;">
                                        HAPUS
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="border border-gray-300 px-4 py-2 text-center text-gray-500 py-4">Belum ada materi yang ditambahkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- MODAL TAMBAH MATERI -->
<div id="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 50; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 8px; padding: 2rem; width: 500px; max-width: 90%;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.25rem; font-weight: bold; margin: 0;">Tambah Materi</h2>
            <button id="closeModal" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #6b7280;">✖</button>
        </div>

        <form action="{{ route('kegiatan.materi.store', $kegiatan->id) }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">*Judul Materi</label>
                <input type="text" name="name" required class="w-full border border-gray-300 rounded-md p-2 focus:ring-teal-500 focus:border-teal-500" placeholder="Ketik jenis materi..." value="{{ old('name') }}">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">*JPL</label>
                <input type="number" name="value" step="0.1" min="0.1" required class="w-full border border-gray-300 rounded-md p-2 focus:ring-teal-500 focus:border-teal-500" placeholder="0" value="{{ old('value') }}">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
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