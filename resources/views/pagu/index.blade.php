<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah dan Edit Pagu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('assets/css/LayoutSuperAdmin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/indexpagu.css') }}">
    <link rel="stylesheet" href="../../../CSS/layout.css">
    <link rel="stylesheet" href="../../../CSS/indexpagu.css">
</head>
<body>

<!-- OVERLAY MODAL -->
<div class="modal-overlay" id="modalPagu">

    <!-- MODAL -->
    <div class="modal-box">
        <div class="modal-header">
            <h2>PENGATURAN PAGU ANGGARAN</h2>
            <button class="close-btn" id="closeModal">✕</button>
        </div>

        <div class="modal-body">
            <div class="form-group">
                <label>No. RKAKL</label>
                <input type="text">
            </div>

            <div class="form-group">
                <label>Kategori Pagu</label>
                <input type="text">
            </div>

            <div class="form-group">
                <label>Submark</label>
                <input type="text">
            </div>

            <div class="form-group">
                <label>Pagu</label>
                <input type="text">
            </div>

            <button class="btn-save">
                💾 SIMPAN
            </button>
        </div>
    </div>

</div>
{{-- LAYOUT LOAD --}}
<div data-include="{{ asset('layout/LayoutSuperAdmin.html') }}"></div>
<script src="{{ asset('assets/JS/LayoutSuperAdmin.js') }}"></script>
<script src="{{ asset('assets/JS/indexpagu.js') }}"></script>

</body>
</html>
