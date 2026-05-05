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

    {{-- Info Justifikasi --}}
    <x-detail-section title="Informasi Justifikasi" icon="fa-info-circle">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-1">
            <x-detail-row label="Tujuan">{{ $kegiatan->tujuan ?? '-' }}</x-detail-row>
            <x-detail-row label="Justifikasi">{{ $kegiatan->justifikasi ?? '-' }}</x-detail-row>
            <x-detail-row label="Target Kompetensi">{{ $kegiatan->target_kompetensi ?? '-' }}</x-detail-row>
        </div>
    </x-detail-section>

    {{-- Target Kegiatan --}}
    <x-detail-section title="Target Kegiatan" icon="fa-bullseye">
        @php $existingNumbers = $kegiatan->activityTargets->pluck('target_number')->toArray(); @endphp
        <div class="flex justify-end mb-4">
            @if (count($existingNumbers) < 3)
                <button id="openAddModal"
                    class="bg-[#007a7a] hover:bg-[#005f5f] text-white font-semibold px-4 py-2 rounded-lg text-sm transition">
                    + Tambah Target
                </button>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse border border-gray-200">
                <thead>
                    <tr>
                        <th class="bg-[#007a7a] text-white py-3 px-4 font-semibold text-sm text-center w-28 border border-white">Target</th>
                        <th class="bg-[#007a7a] text-white py-3 px-4 font-semibold text-sm text-left border border-white">Deskripsi</th>
                        <th class="bg-[#007a7a] text-white py-3 px-4 font-semibold text-sm text-center w-36 border border-white">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse ($kegiatan->activityTargets as $target)
                        <tr>
                            <td class="text-center font-semibold border border-gray-200 py-3 px-4">
                                Target {{ $target->target_number }}
                            </td>
                            <td class="border border-gray-200 py-3 px-4">{{ $target->description }}</td>
                            <td class="text-center border border-gray-200 py-3 px-4">
                                <button onclick="openEditModal({{ $target->id }}, {{ $target->target_number }}, {{ json_encode($target->description) }})"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition mr-1">
                                    EDIT
                                </button>
                                <form action="{{ route('kegiatan.target.destroy', ['kegiatan' => $kegiatan->id, 'id' => $target->id]) }}"
                                    method="POST" class="inline-block"
                                    onsubmit="return confirm('Hapus Target {{ $target->target_number }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                        HAPUS
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-gray-500 border border-gray-200 py-3 px-4">
                                Belum ada target yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-detail-section>
</section>

<!-- MODAL TAMBAH TARGET -->
<div id="addModal" style="display:none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-lg p-8 w-[480px] max-w-[90%]">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-bold m-0">Tambah Target</h2>
            <button onclick="closeAddModal()" class="bg-transparent border-none text-2xl cursor-pointer text-gray-500">✖</button>
        </div>
        <form action="{{ route('kegiatan.target.store', $kegiatan->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Target</label>
                <select name="target_number" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                    @foreach ([1, 2, 3] as $num)
                        @if (!in_array($num, $existingNumbers))
                            <option value="{{ $num }}">Target {{ $num }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="description" rows="4" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm resize-y"
                    placeholder="Tuliskan deskripsi target...">{{ old('description') }}</textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeAddModal()"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-4 py-2 rounded-lg text-sm transition">
                    Batal
                </button>
                <button type="submit"
                    class="bg-[#007a7a] hover:bg-[#005f5f] text-white font-semibold px-4 py-2 rounded-lg text-sm transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT TARGET -->
<div id="editModal" style="display:none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-lg p-8 w-[480px] max-w-[90%]">
        <div class="flex justify-between items-center mb-6">
            <h2 id="editModalTitle" class="text-lg font-bold m-0">Edit Target</h2>
            <button onclick="closeEditModal()" class="bg-transparent border-none text-2xl cursor-pointer text-gray-500">✖</button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea id="editDescription" name="description" rows="4" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm resize-y"></textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditModal()"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-4 py-2 rounded-lg text-sm transition">
                    Batal
                </button>
                <button type="submit"
                    class="bg-[#007a7a] hover:bg-[#005f5f] text-white font-semibold px-4 py-2 rounded-lg text-sm transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('addModal').style.display = 'flex';
    }
    function closeAddModal() {
        document.getElementById('addModal').style.display = 'none';
    }
    function openEditModal(id, number, description) {
        document.getElementById('editModalTitle').textContent = 'Edit Target ' + number;
        document.getElementById('editDescription').value = description;
        document.getElementById('editForm').action = '/kegiatan/{{ $kegiatan->id }}/target/' + id;
        document.getElementById('editModal').style.display = 'flex';
    }
    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    document.getElementById('openAddModal')?.addEventListener('click', openAddModal);
</script>