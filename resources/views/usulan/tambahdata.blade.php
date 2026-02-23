<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Tambah Data Kegiatan</title>

  <link rel="stylesheet" href="{{ asset('assets/css/LayoutPengusul.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/tambahdata.css') }}">
</head>

<body>

  <div class="content input-page">
    <h3 class="title">Data Kegiatan</h3>

    <div class="form-card">
        <div class="form-row">
            <label>Tanggal/Bulan/Tahun</label>
            <input type="date">
        </div>

        <div class="form-row">
            <label>No. Surat</label>
            <input type="text">
        </div>

        <div class="form-row">
            <label>Nama Kegiatan</label>
            <select><option>-PILIH-</option></select>
        </div>

        <div class="form-row">
            <label>Jenis Kegiatan</label>
            <select><option>-PILIH-</option></select>
        </div>

        <div class="form-row">
            <label>Cakupan Kegiatan</label>
            <select><option>-PILIH-</option></select>
        </div>

        <div class="form-row">
            <label>Jenis Materi</label>
            <select><option>-PILIH-</option></select>
        </div>

        <div class="form-row">
            <label>Metode</label>
            <select><option>-PILIH-</option></select>
        </div>

        <div class="form-row">
            <label>Angkatan</label>
            <select id="angka">
                <option>-PILIH-</option>
            </select>
        </div>

        <div class="form-row">
            <label>Bentuk</label>
            <select><option>-PILIH-</option></select>
        </div>

        <div class="form-row">
            <label>Institusi Kerjasama</label>
            <input type="text">
        </div>

        <div class="form-row">
            <label>Target Peserta</label>
            <select><option>-PILIH-</option></select>
        </div>

        <div class="form-row two">
            <label>Waktu</label>
            <input type="time">
            <input type="time">
        </div>

        <div class="form-row">
            <label>Anggaran</label>
            <input type="text">
        </div>

        <div class="form-row">
            <label>Unit Pengusul</label>
            <input type="text">
        </div>

        <div class="form-row">
            <label>Nama PIC</label>
            <input type="text">
        </div>

        <div class="form-row">
            <label>WA PIC</label>
            <input type="text">
        </div>

        <div class="form-action">
            <button id="btnSave" class="btn-save">💾 SIMPAN</button>
            <button id="btnCancel" class="btn-cancel">✖ BATAL</button>
        </div>

    </div>
  </div>

  <script src="{{ asset('assets/js/LayoutPengusul.js') }}"></script>
  <script src="{{ asset('assets/js/tambahdata.js') }}"></script>

</body>
</html>
