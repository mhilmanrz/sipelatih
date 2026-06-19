<section>
    <div>

        <div class="flex justify-center gap-4 mb-8">
            <button onclick="openModal()"
                class="bg-[#007a7a] hover:bg-[#005f5f] text-white font-semibold px-4 py-2 rounded-lg text-sm transition cursor-pointer">
                Unggah KAK Baru
            </button>

            @if($kegiatan->activityKakFiles->isNotEmpty())
                @php
                    $latestKak = $kegiatan->activityKakFiles->last();
                @endphp
                <a href="{{ asset('storage/' . $latestKak->url) }}" download
                    class="bg-blue-500 hover:bg-blue-600 text-white px-3.5 py-2 rounded-lg text-sm font-semibold transition cursor-pointer inline-flex items-center gap-2">
                    <i class="fa fa-download"></i> Unduh KAK Terkini
                </a>
            @else
                <button onclick="alert('Belum ada file KAK yang diunggah.')"
                    class="bg-gray-300 text-gray-500 px-3.5 py-2 rounded-lg text-sm font-semibold cursor-not-allowed" disabled>
                    Unduh KAK Terkini
                </button>
            @endif
        </div>

        <div class="border border-gray-300 h-[500px] bg-gray-50 flex items-center justify-center rounded">
            @if($kegiatan->activityKakFiles->isNotEmpty())
                @php
                    $latestKak = $kegiatan->activityKakFiles->last();
                @endphp
                @if(str_ends_with($latestKak->url, '.pdf'))
                    <iframe src="{{ asset('storage/' . $latestKak->url) }}" width="100%" height="100%"></iframe>
                @else
                    <div class="text-center p-4">
                        <i class="fa fa-file-word text-[#007a7a] text-5xl mb-3"></i>
                        <p class="text-gray-700 font-semibold mb-1">{{ basename($latestKak->url) }}</p>
                        <p class="text-xs text-gray-500">Pratinjau hanya didukung untuk format PDF.</p>
                        <a href="{{ asset('storage/' . $latestKak->url) }}" target="_blank" class="mt-4 inline-flex items-center gap-2 bg-[#007a7a] hover:bg-[#005f5f] text-white font-semibold px-4 py-2 rounded-lg text-sm transition cursor-pointer">
                            <i class="fa fa-download"></i> Unduh File KAK
                        </a>
                    </div>
                @endif
            @else
                <p class="text-gray-500 italic">Pratinjau KAK belum tersedia.</p>
            @endif
        </div>
    </div>
</section>

<!-- MODAL UPLOAD -->
<div id="modal"
    class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-8 w-[400px] max-w-[90%]">

        <form action="{{ route('kegiatan.upload-kak', $kegiatan->id) }}" method="POST" enctype="multipart/form-data" id="upload-form">
            @csrf
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold m-0">Unggah Dokumen KAK</h2>
                <button type="button" onclick="closeModal()"
                    class="bg-transparent border-none text-2xl cursor-pointer text-gray-500">✖</button>
            </div>

            <div onclick="document.getElementById('kak_file').click()"
                class="border-2 border-dashed border-gray-300 p-8 text-center rounded-lg mb-6 cursor-pointer hover:bg-gray-50 transition">
                <div class="text-4xl text-gray-400 mb-2">⬆</div>
                <p class="m-0 text-gray-600 font-medium">Klik atau seret file ke sini</p>
                <p class="text-xs text-gray-400 mt-1">Format: PDF, DOC, DOCX (Maks. 10MB)</p>
                <p id="file-name-display" class="text-sm font-semibold text-[#007a7a] mt-2 hidden"></p>
            </div>

            <input type="file" id="kak_file" name="kak_file" class="hidden" accept=".pdf,.doc,.docx" onchange="displayFileName(this)">

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal()"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-4 py-2 rounded-lg text-sm transition cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                    class="bg-[#007a7a] hover:bg-[#005f5f] text-white font-semibold px-4 py-2 rounded-lg text-sm transition cursor-pointer">
                    Simpan
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    function openModal() {
        document.getElementById('modal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('modal').classList.add('hidden');
        document.getElementById('upload-form').reset();
        const display = document.getElementById('file-name-display');
        display.textContent = '';
        display.classList.add('hidden');
    }

    function displayFileName(input) {
        const display = document.getElementById('file-name-display');
        if (input.files && input.files.length > 0) {
            display.textContent = 'Terpilih: ' + input.files[0].name;
            display.classList.remove('hidden');
        } else {
            display.textContent = '';
            display.classList.add('hidden');
        }
    }
</script>
