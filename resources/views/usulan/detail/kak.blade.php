<section>
    <div>

        <div class="flex justify-center gap-4 mb-8">
            <button onclick="openModal()"
                class="bg-[#007a7a] hover:bg-[#005f5f] text-white font-semibold px-4 py-2 rounded-lg text-sm transition cursor-pointer">
                Unggah KAK Baru
            </button>

            <button onclick="alert('Fitur unduh akan diaktifkan setelah sistem penyimpanan file KAK (Storage) dirangkai.')"
                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer">
                Unduh KAK Terkini
            </button>
        </div>

        <div
            class="border border-gray-300 h-[500px] bg-gray-50 flex items-center justify-center rounded">
            <p class="text-gray-500 italic">Pratinjau KAK belum tersedia.</p>
            <!-- Nanti akan diganti dengan: <iframe src="{{ asset('storage/' . $kegiatan->kak_file_path) }}" width="100%" height="100%"></iframe> -->
        </div>
    </div>
</section>

<!-- MODAL UPLOAD -->
<div id="modal"
    class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-8 w-[400px] max-w-[90%]">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold m-0">Unggah Dokumen KAK</h2>
            <button onclick="closeModal()"
                class="bg-transparent border-none text-2xl cursor-pointer text-gray-500">✖</button>
        </div>

        <div
            class="border-2 border-dashed border-gray-300 p-8 text-center rounded-lg mb-6 cursor-pointer">
            <div class="text-4xl text-gray-400 mb-2">⬆</div>
            <p class="m-0 text-gray-600">Klik atau seret file ke sini</p>
        </div>

        <input type="file" class="block w-full mb-6">

        <div class="flex justify-end gap-2">
            <button onclick="closeModal()"
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-4 py-2 rounded-lg text-sm transition cursor-pointer">
                Batal
            </button>
            <button onclick="saveFile()"
                class="bg-[#007a7a] hover:bg-[#005f5f] text-white font-semibold px-4 py-2 rounded-lg text-sm transition cursor-pointer">
                Simpan
            </button>
        </div>

    </div>
</div>

<script>
    function openModal() {
        document.getElementById('modal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('modal').classList.add('hidden');
    }

    function saveFile() {
        alert('Fitur simpan file sedang dalam pengembangan 👷‍♂️');
        closeModal();
    }
</script>
