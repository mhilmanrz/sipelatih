<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Pegawai</title>

    <link rel="stylesheet" href="{{ asset('assets/css/LayoutSuperAdmin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tambahmanajemen.css') }}">
</head>

<body>

<!-- CONTENT -->
<div class="content">

  <h1>Tambah Peserta</h1>

  <div class="panel-wrapper">

    <!-- PESERTA TERPILIH -->
    <div class="panel">
      <div class="panel-header">Peserta Terpilih</div>

      <input
        type="text"
        class="search"
        placeholder="Cari Berdasarkan NIP atau Nama"
      >

      <ul id="selectedList" class="list"></ul>
    </div>

    <!-- DAFTAR PESERTA -->
    <div class="panel">
      <div class="panel-header">Daftar Peserta</div>

      <input
        type="text"
        id="searchAll"
        class="search"
        placeholder="Cari Peserta"
      >

      <ul id="participantList" class="list"></ul>

      <div class="footer-info">
        Total <span id="totalCount">0</span> Items
      </div>
    </div>

  </div>

  <div class="action-buttons">
    <button class="btn-save">💾 Simpan</button>
    <button onclick="resetData()" class="btn-reset">↺ Reset</button>
  </div>

</div>

{{-- LAYOUT LOAD --}}
<div data-include="{{ asset('assets/layout/LayoutSuperAdmin.html') }}"></div>
<script src="{{ asset('assets/js/LayoutSuperAdmin.js') }}"></script>
<script src="{{ asset('assets/js/tambahmanajemen.js') }}"></script>

</body>
</html>
