<x-layouts.app>
    <x-slot:title>Import Nilai Peserta</x-slot>

    @push('styles')
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="{{ asset('assets/css/LayoutSuperAdmin.css') }}">
    @endpush

    <div class="p-8">
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold uppercase" style="color: white; font-size: 1.5rem; background-color: #0369a1; padding: 1rem 2rem; border-radius: 8px; display: inline-block;">IMPORT NILAI PESERTA</h1>

            <a href="{{ route('kegiatan.show', ['kegiatan' => $kegiatan->id, 'tab' => 'input-nilai']) }}" class="text-gray-600 hover:text-gray-900 font-medium bg-white px-4 py-2 rounded shadow transition-colors">
                ← Kembali ke Detail Kegiatan
            </a>
        </div>

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <!-- STEP 1: Download Template -->
            <div class="bg-white rounded-xl shadow p-8 border-t-4 border-sky-600">
                <div class="flex items-center gap-4 mb-4">
                    <div class="bg-sky-100 text-sky-700 rounded-full h-12 w-12 flex items-center justify-center font-bold text-xl">1</div>
                    <h2 class="text-xl font-bold text-gray-800">Unduh Format Excel</h2>
                </div>

                <p class="text-gray-600 mb-6 leading-relaxed">
                    Pastikan Anda menggunakan format Excel resmi yang kami sediakan untuk menginput nilai pada kegiatan <span class="font-bold">"{{ $kegiatan->activityName->name ?? $kegiatan->name }}"</span>. NIP Peserta digunakan sebagai kunci utama (*Primary Key*).
                </p>

                <a href="{{ route('kegiatan.input-nilai.template', $kegiatan->id) }}" class="inline-flex py-3 px-6 bg-gray-800 hover:bg-gray-900 text-white font-semibold rounded-lg shadow items-center transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Download Template Nilai (.xlsx)
                </a>
            </div>

            <!-- STEP 2: Upload Files -->
            <div class="bg-white rounded-xl shadow p-8 border-t-4 border-sky-600">
                <div class="flex items-center gap-4 mb-4">
                    <div class="bg-sky-100 text-sky-700 rounded-full h-12 w-12 flex items-center justify-center font-bold text-xl">2</div>
                    <h2 class="text-xl font-bold text-gray-800">Unggah Data Nilai</h2>
                </div>

                <p class="text-gray-600 mb-6">
                    Arahkan file <span class="font-semibold text-gray-800">.xlsx</span> yang sudah Anda lengkapi nilainya ke area di bawah ini. Proses simpan dilakukan *background* (antrean queue).
                </p>

                <form action="{{ route('kegiatan.input-nilai.import.store', $kegiatan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:bg-gray-50 transition-colors">
                        <label for="excel_file" class="cursor-pointer flex flex-col items-center justify-center">
                            <svg class="w-16 h-16 text-sky-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span class="text-gray-600 font-medium text-lg leading-tight mb-1">Klik untuk Memilih File</span>
                            <span class="text-sm text-gray-400">Atau tarik file ke kotak ini</span>

                            <input type="file" id="excel_file" name="file_excel" class="hidden" accept=".xlsx, .xls, .csv" required onchange="document.getElementById('file-name').innerText = this.files[0].name" />
                        </label>
                    </div>

                    <div id="file-name" class="mt-4 text-center font-medium text-sky-700 h-6"></div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-sky-600 hover:bg-sky-700 text-white font-bold py-3 px-8 rounded-full shadow-lg transition-colors w-full md:w-auto">
                            Mulai Import Nilai
                        </button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</x-layouts.app>
