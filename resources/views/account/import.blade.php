<x-layouts.app>
    <x-slot:title>Import Pegawai</x-slot>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/LayoutSuperAdmin.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/importmanajemenpegawai.css') }}">
    @endpush

    <div class="wrap">
        <div class="page-title">IMPORT AKUN</div>

        @if (session('error'))
            <div style="padding: 15px; background-color: #f8d7da; color: #721c24; margin-bottom: 20px; border-radius: 5px;">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div style="padding: 15px; background-color: #f8d7da; color: #721c24; margin-bottom: 20px; border-radius: 5px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- TEMPLATE CARD -->
        <div class="card">
            <h3>TEMPLATE</h3>
            <div class="template-row">
                <div style="color:#2B6B71;font-weight:700;font-size:12px;opacity:.8">
                    Unduh template Excel, isi datanya, lalu unggah di bawah.
                </div>
<a href="{{ route('accounts.import.template') }}" class="btn" style="text-decoration:none;">
                    <span aria-hidden="true">⬇️</span>
                    Download Template
                </a>
            </div>
        </div>

        <!-- IMPORT CARD -->
        <div class="card">
            <h3>IMPORT PESERTA</h3>

            <form action="{{ route('accounts.import') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="import-footer" style="margin-top:20px; margin-bottom: 20px;">
                    <div class="file-wrap">
                        <label class="fake" for="fileInput">Choose File</label>
                        <div class="name" id="fileName">No file chosen</div>
                        <input name="file" id="fileInput" type="file"
                            accept=".csv,text/csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel"
                            required />
                    </div>
                </div>

                <div class="note" style="margin-bottom: 20px;">
Format file harus <b>Excel (.xlsx, .xls)</b> atau <b>CSV</b> dengan header minimal:
                    <b>nama, email, password, unit_kerja, role</b>.<br />
                    Validasi: nama dan email tidak kosong; role harus sesuai dengan role yang tersedia di sistem.
                </div>

                <button type="submit" class="btn save"
                    style="background:#00B8A5; color:white; padding:10px 20px; border:none; border-radius:5px; cursor:pointer;">
                    <span aria-hidden="true">💾</span>
                    SIMPAN JALANKAN IMPORT
                </button>
                <a href="{{ route('accounts.index') }}" class="btn"
                    style="padding:10px 20px; border:1px solid #ccc; border-radius:5px; text-decoration:none; color:#D6DE20; margin-left: 10px;">Batal</a>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('assets/js/LayoutSuperAdmin.js') }}"></script>
        <script>
            document.getElementById('fileInput').addEventListener('change', function(e) {
                var fileName = e.target.files[0] ? e.target.files[0].name : "No file chosen";
                document.getElementById('fileName').textContent = fileName;
            });
        </script>
    @endpush
</x-layouts.app>
