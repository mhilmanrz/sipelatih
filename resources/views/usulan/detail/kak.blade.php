<!-- KAK CONTENT CARD -->
<section style="margin-top: 2rem;">
    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 2rem; text-align: center;">

        <h3 style="font-size: 1.25rem; font-weight: 600; color: #374151; margin-bottom: 1rem;">Dokumen KAK (Kerangka
            Acuan Kerja)</h3>

        <div style="display: flex; justify-content: center; gap: 1rem; margin-bottom: 2rem;">
            <button onclick="openModal()"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded shadow transition-colors"
                style="cursor: pointer;">
                Unggah KAK Baru
            </button>

            <button class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded shadow transition-colors"
                style="cursor: pointer;"
                onclick="alert('Fitur unduh akan diaktifkan setelah sistem penyimpanan file KAK (Storage) dirangkai.')">
                Unduh KAK Terkini
            </button>
        </div>

        <div
            style="border: 1px solid #d1d5db; height: 500px; background: #f9fafb; display: flex; align-items: center; justify-content: center; border-radius: 4px;">
            <p style="color: #6b7280; font-style: italic;">Pratinjau KAK belum tersedia.</p>
            <!-- Nanti akan diganti dengan: <iframe src="{{ asset('storage/' . $kegiatan->kak_file_path) }}" width="100%" height="100%"></iframe> -->
        </div>
    </div>
</section>

<!-- MODAL UPLOAD -->
<div id="modal"
    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 50; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 8px; padding: 2rem; width: 400px; max-width: 90%;">

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.25rem; font-weight: bold; margin: 0;">Unggah Dokumen KAK</h2>
            <button onclick="closeModal()"
                style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #6b7280;">✖</button>
        </div>

        <div
            style="border: 2px dashed #d1d5db; padding: 2rem; text-align: center; border-radius: 8px; margin-bottom: 1.5rem; cursor: pointer;">
            <div style="font-size: 2rem; color: #9ca3af; margin-bottom: 0.5rem;">⬆</div>
            <p style="margin: 0; color: #4b5563;">Klik atau seret file ke sini</p>
        </div>

        <input type="file" style="display: block; width: 100%; margin-bottom: 1.5rem;">

        <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
            <button onclick="closeModal()"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded transition-colors"
                style="cursor: pointer;">
                Batal
            </button>
            <button onclick="saveFile()"
                class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded shadow transition-colors"
                style="cursor: pointer;">
                Simpan
            </button>
        </div>

    </div>
</div>

<script>
    function openModal() {
        document.getElementById('modal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('modal').style.display = 'none';
    }

    function saveFile() {
        alert('Fitur simpan file sedang dalam pengembangan 👷‍♂️');
        closeModal();
    }
</script>
