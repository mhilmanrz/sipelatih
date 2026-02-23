<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Import Pegawai</title>

  <link rel="stylesheet" href="{{ asset('assets/css/LayoutSuperAdmin.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/importmanajemen.css') }}">
</head>

<body>
  <div class="wrap">
    <div class="page-title">IMPORT PESERTA</div>

    <!-- TEMPLATE CARD -->
    <div class="card">
      <h3>TEMPLATE</h3>
      <div class="template-row">
        <div style="color:#2B6B71;font-weight:700;font-size:12px;opacity:.8">
          Unduh template Excel, isi datanya, lalu unggah di bawah.
        </div>
        <button class="btn" id="btnDownload">
          <span aria-hidden="true">⬇️</span>
          Download Template
        </button>
      </div>
    </div>

    <!-- IMPORT CARD -->
    <div class="card">
      <h3>IMPORT PESERTA</h3>

      <div id="dropzone" class="dropzone" role="button" tabindex="0" aria-label="Unggah file CSV">
        <div class="center">
          <div class="cloud" aria-hidden="true">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none">
              <path d="M7 18a4 4 0 1 1 .6-7.96A5.5 5.5 0 0 1 18.5 9a4.5 4.5 0 0 1 0 9H7Z" stroke="#555" stroke-width="1.8" />
              <path d="M12 12v6" stroke="#555" stroke-width="1.8" stroke-linecap="round"/>
              <path d="M9.5 14.5 12 12l2.5 2.5" stroke="#555" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <div>Unggah</div>
        </div>
      </div>

      <div class="import-footer">
        <div class="file-wrap">
          <label class="fake" for="fileInput">Choose File</label>
          <div class="name" id="fileName">No file chosen</div>
          <input id="fileInput" type="file" accept=".csv,text/csv" />
        </div>

        <button class="btn save" id="btnSave" disabled>
          <span aria-hidden="true">💾</span>
          SIMPAN
        </button>
      </div>

      <div class="preview" id="preview">
        <div class="meta">
          <div>
            <span id="metaRows">0 baris</span> •
            <span id="metaValid">0 valid</span> •
            <span class="bad" id="metaInvalid">0 invalid</span>
          </div>
          <div id="metaMsg"></div>
        </div>
        <div style="max-height:220px;overflow:auto">
          <table id="previewTable"></table>
        </div>
      </div>

      <div class="note">
        Format wajib CSV dengan header: <b>nama,email,telepon</b> (boleh tambah kolom lain).<br />
        Validasi: nama tidak kosong; email format dasar; telepon minimal 8 digit (angka saja).
      </div>
    </div>
  </div>

  <div class="toast" id="toast"></div>
  
  {{-- INCLUDE LAYOUT --}}
  <div data-include="{{ asset('assets/layout/LayoutSuperAdmin.html') }}"></div>

  <script src="{{ asset('assets/js/LayoutSuperAdmin.js') }}"></script>
  <script src="{{ asset('assets/js/importmanajemen.js') }}"></script>
</body>
</html>