<x-layouts.app>
    <x-slot:title>Import Akun</x-slot>

    <div class="tw-wrap p-6 max-w-2xl mx-auto">
        <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
            <h1 class="text-2xl font-bold text-white border-b pb-2">IMPORT AKUN</h1>
            <a href="{{ route('accounts.index') }}"
                class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 bg-white border border-gray-300 px-4 py-2 rounded-lg transition">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-2">TEMPLATE</h3>
            <div class="flex items-center justify-between border border-gray-200 bg-gray-50 rounded p-4">
                <div class="text-sm font-semibold text-[#2B6B71]">
                    Unduh template Excel, isi datanya, lalu unggah di bawah.
                </div>
                <a href="#" class="inline-flex items-center text-sm font-bold text-teal-700 hover:text-teal-900 hover:underline">
                    <span class="mr-1">⬇️</span> Download Template
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">PILIH FILE IMPORT</h3>

            <form action="{{ route('accounts.import') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">File Excel / CSV</label>
                    <div class="flex items-center gap-3">
                        <label class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md cursor-pointer hover:bg-gray-300 font-medium text-sm transition">
                            Choose File
                            <input name="file" id="fileInput" type="file" class="hidden"
                                accept=".csv,text/csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel" required />
                        </label>
                        <span id="fileName" class="text-sm text-gray-500">No file chosen</span>
                    </div>
                </div>

                <div class="text-sm text-gray-600 bg-gray-50 p-3 rounded mb-6 border border-gray-200">
                    Format file harus <b>Excel (.xlsx, .xls)</b> atau <b>CSV</b> dengan header minimal: <b>nama,email,password,role</b>.<br />
                    Validasi: pastikan role sesuai dengan nama role yang ada di sistem.
                </div>

                <div class="flex items-center justify-start space-x-3 pt-4 border-t border-gray-200">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-teal-600 text-white font-semibold rounded hover:bg-teal-700 transition-colors shadow">
                        <span class="mr-2">💾</span> SIMPAN & JALANKAN IMPORT
                    </button>
                    <a href="{{ route('accounts.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded hover:bg-gray-100 transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('fileInput').addEventListener('change', function(e) {
                var fileName = e.target.files[0] ? e.target.files[0].name : "No file chosen";
                document.getElementById('fileName').textContent = fileName;
            });
        </script>
    @endpush
</x-layouts.app>
