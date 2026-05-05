<section>

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

    <!-- TABLE -->
    <div>
        <div class="flex justify-start mb-4">
            <button id="openModal" class="bg-[#007a7a] hover:bg-[#005f5f] text-white font-semibold px-4 py-2 rounded-lg text-sm transition">
                + Tambah Materi
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse border border-gray-200">
                <thead>
                    <tr class="bg-[#007a7a] text-white text-sm">
                        <th class="text-center w-16 border border-white py-3 px-4 font-semibold">NO.</th>
                        <th class="text-left border border-white py-3 px-4 font-semibold">Materi</th>
                        <th class="text-center w-24 border border-white py-3 px-4 font-semibold">JPL</th>
                        <th class="text-center w-24 border border-white py-3 px-4 font-semibold">JPL / 45 Menit</th>
                        <th class="text-center w-32 border border-white py-3 px-4 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody id="materiTableBody" class="bg-white">
                    @forelse ($kegiatan->activityMaterials as $index => $item)
                        <tr>
                        <td class="text-center border border-gray-200 py-3 px-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                        <td class="border border-gray-200 py-3 px-4 text-sm text-gray-900">{{ $item->name }}</td>
                        <td class="text-center border border-gray-200 py-3 px-4 text-sm text-gray-900">{{ $item->value }}</td>
                        <td class="text-center border border-gray-200 py-3 px-4 text-sm text-gray-900">{{ round($item->value / 45, 2) }}</td>
                        <td class="text-center border border-gray-200 py-3 px-4">
                                <form action="{{ route('kegiatan.materi.destroy', ['kegiatan' => $kegiatan->id, 'id' => $item->id]) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-gray-500 border border-gray-200 py-3 px-4 text-sm">Belum ada materi yang ditambahkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- MODAL TAMBAH MATERI -->
<div id="modal" style="display: none;" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-8 w-[500px] max-w-[90%]">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold m-0">Tambah Materi</h2>
            <button id="closeModal" class="bg-transparent border-none text-2xl cursor-pointer text-gray-500">✖</button>
        </div>

        <form action="{{ route('kegiatan.materi.store', $kegiatan->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">*Judul Materi</label>
                <input type="text" name="name" required class="w-full border border-gray-300 rounded-md p-2 focus:ring-teal-500 focus:border-teal-500" placeholder="Ketik jenis materi..." value="{{ old('name') }}">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">*JPL</label>
                <input type="number" name="value" step="0.1" min="0.1" required class="w-full border border-gray-300 rounded-md p-2 focus:ring-teal-500 focus:border-teal-500" placeholder="0" value="{{ old('value') }}">
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" id="cancelBtn" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-4 py-2 rounded-lg text-sm transition">
                    Batal
                </button>
                <button type="submit" class="bg-[#007a7a] hover:bg-[#005f5f] text-white font-semibold px-4 py-2 rounded-lg text-sm transition">
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