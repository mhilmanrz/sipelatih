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

    <!-- TABLE CARD -->
    <div class="mb-6">
        <div class="flex justify-start mb-4">
            <button id="openModal" class="bg-[#007a7a] hover:bg-[#005f5f] text-white font-semibold px-4 py-2 rounded-lg text-sm transition">
                + Tambah Sasaran Profesi
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse border border-gray-200">
                <thead class="bg-[#007a7a] text-white py-3 px-4 font-semibold text-sm">
                    <tr>
                        <th class="text-center w-16 border border-white py-3 px-4 font-semibold">NO.</th>
                        <th class="text-left border border-white py-3 px-4 font-semibold">Profesi</th>
                        <th class="text-center w-32 border border-white py-3 px-4 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody id="profesiTableBody" class="bg-white">
                    @forelse ($kegiatan->activityProfessions as $index => $item)
                        <tr>
                            <td class="text-center border border-gray-200 py-3 px-4">{{ $index + 1 }}</td>
                            <td class="border border-gray-200 py-3 px-4">{{ $item->profession->name ?? 'Profesi Tidak Ditemukan' }}</td>
                            <td class="text-center border border-gray-200 py-3 px-4">
                                <form action="{{ route('kegiatan.sasaran-profesi.destroy', ['kegiatan' => $kegiatan->id, 'id' => $item->id]) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus profesi ini dari sasaran?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition inline-block">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-gray-500 border border-gray-200 py-3 px-4">Belum ada sasaran profesi yang ditambahkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- MODAL TAMBAH -->
<div id="modal" style="display: none;" class="fixed inset-0 bg-black/50 z-50 items-center justify-center">
    <div class="bg-white rounded-lg p-8 w-[500px] max-w-[90%]">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold m-0">Tambah Sasaran Profesi</h2>
            <button id="closeModal" class="bg-transparent border-none text-2xl cursor-pointer text-gray-500">✖</button>
        </div>

        <form action="{{ route('kegiatan.sasaran-profesi.store', $kegiatan->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2 text-gray-700">*Sasaran Profesi</label>
                <select name="profession_id" id="professionSelect" required class="w-full border border-gray-300 rounded-md p-2 focus:ring-teal-500 focus:border-teal-500">
                    <option value="">- PILIH PROFESI -</option>
                    @foreach ($professions as $prof)
                        <option value="{{ $prof->id }}">{{ $prof->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end gap-2 mt-6">
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
