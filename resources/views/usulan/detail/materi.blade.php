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
        <div class="flex justify-start gap-2 mb-4">
            <button id="openModal" class="bg-[#007a7a] hover:bg-[#005f5f] text-white font-semibold px-4 py-2 rounded-lg text-sm transition">
                + Tambah Materi
            </button>
            <a href="{{ route('kegiatan.materi.import.page', $kegiatan->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-4 py-2 rounded-lg text-sm transition inline-block no-underline">
                ⬇ Import Materi
            </a>
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
                            <div class="flex items-center justify-center gap-1.5">
                                <button type="button"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition btn-edit"
                                        data-id="{{ $item->id }}"
                                        data-name="{{ $item->name }}"
                                        data-value="{{ $item->value }}">
                                    Edit
                                </button>
                                <form action="{{ route('kegiatan.materi.destroy', ['kegiatan' => $kegiatan->id, 'id' => $item->id]) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
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

<!-- MODAL MATERI (TAMBAH / EDIT) -->
<div id="modal" style="display: none;" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-8 w-[500px] max-w-[90%]">
        <div class="flex justify-between items-center mb-6">
            <h2 id="modalTitle" class="text-xl font-bold m-0">Tambah Materi</h2>
            <button id="closeModal" class="bg-transparent border-none text-2xl cursor-pointer text-gray-500">✖</button>
        </div>

        <form id="materiForm" action="{{ route('kegiatan.materi.store', $kegiatan->id) }}" method="POST">
            @csrf
            <div id="methodField"></div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">*Judul Materi</label>
                <input type="text" id="materiName" name="name" required class="w-full border border-gray-300 rounded-md p-2 focus:ring-teal-500 focus:border-teal-500" placeholder="Ketik jenis materi..." value="{{ old('name') }}">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">*Menit</label>
                <input type="number" id="materiValue" name="value" step="0.1" min="0.1" required class="w-full border border-gray-300 rounded-md p-2 focus:ring-teal-500 focus:border-teal-500" placeholder="0" value="{{ old('value') }}">
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
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('modal');
        const form = document.getElementById('materiForm');
        const modalTitle = document.getElementById('modalTitle');
        const methodField = document.getElementById('methodField');
        const nameInput = document.getElementById('materiName');
        const valueInput = document.getElementById('materiValue');

        document.getElementById('openModal')?.addEventListener('click', function() {
            modalTitle.textContent = 'Tambah Materi';
            form.action = "{{ route('kegiatan.materi.store', $kegiatan->id) }}";
            methodField.innerHTML = '';
            nameInput.value = '';
            valueInput.value = '';
            modal.style.display = 'flex';
        });

        document.querySelectorAll('.btn-edit').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const value = this.getAttribute('data-value');

                modalTitle.textContent = 'Edit Materi';
                form.action = `/kegiatan/{{ $kegiatan->id }}/materi/${id}`;
                methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
                nameInput.value = name;
                valueInput.value = value;
                modal.style.display = 'flex';
            });
        });

        document.getElementById('closeModal')?.addEventListener('click', function() {
            modal.style.display = 'none';
        });
        document.getElementById('cancelBtn')?.addEventListener('click', function() {
            modal.style.display = 'none';
        });
    });
</script>