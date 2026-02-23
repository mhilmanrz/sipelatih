@extends('layout.LayoutPengusul')

@section('content')

<link rel="stylesheet" href="{{ asset('css/kegiatan.css') }}">
<link rel="stylesheet" href="{{ asset('css/LayoutPengusul.css') }}">

<div class="layout">

    <div class="content">

        <!-- MAIN -->
        <main class="main">

            <!-- HEADER -->
            <div class="page-header">
                PENGAJUAN DIKLAT
            </div>

            <!-- INFO CARD -->
            <section class="info-card">
                <div class="info-grid">
                    <div>Nama Kegiatan</div><div>: Workshop ICTEC</div>
                    <div>Pengusul</div><div>: RSUPN Dr. Cipto Mangunkusumo</div>
                    <div>Jenis Kegiatan</div><div>: Workshop</div>
                    <div>Cakupan</div><div>: Nasional</div>
                    <div>Jenis Materi</div><div>: Spesifik Keprofesian</div>
                    <div>Waktu Pelaksanaan</div><div>: 1 November 2025 s/d 25 November 2025</div>
                    <div>Status</div><div>: Draft</div>
                </div>
            </section>

            <!-- TABS -->
            <div class="tabs">
        <button onclick="window.location='{{ route('usulan.kegiatan') }}'" class="tab active">Kegiatan</button>
        <button onclick="window.location='{{ route('usulan.sasaran') }}'" class="tab">Sasaran Profesi</button>
        <button onclick="window.location='{{ route('usulan.kak') }}'" class="tab">KAK</button>
        <button onclick="window.location='{{ route('usulan.materi') }}'" class="tab">Materi</button>
        <button onclick="window.location='{{ route('usulan.narasumber') }}'" class="tab">Narasumber</button>
        <button onclick="window.location='{{ route('usulan.peserta') }}'" class="tab">Peserta</button>
        <button onclick="window.location='{{ route('usulan.pengiriman') }}'">Pengiriman</button>
        <button onclick="window.location='{{ route('usulan.penilaian') }}'">Penilaian</button>
        <button onclick="window.location='{{ route('usulan.sertifikat') }}'">Sertifikat</button>
            </div>

            <!-- CONTENT CARD -->
            <section class="content-card">
                <a href="{{ url('project/usulan/tambahdata') }}" class="edit-btn">✎ Edit</a>

                <div class="detail-grid">
                    <div>Tanggal</div><div>27 November 2025</div>
                    <div>No. Surat</div><div>HK.02.03/D.IX/20830/2025</div>
                    <div>Nama Kegiatan</div><div>Pelatihan Rekam Medis</div>
                    <div>Jenis Kegiatan</div><div>Workshop</div>
                    <div>Cakupan</div><div>Manajerial</div>
                    <div>Jenis Materi</div><div>Kurikulum</div>
                    <div>Metode</div><div>Luring</div>
                    <div>Angkatan</div><div>1</div>
                    <div>Bentuk</div><div>Mandiri</div>
                    <div>Target Peserta</div><div>Klasikal</div>
                    <div>Waktu</div><div>1 Nov – 25 Nov 2025</div>
                    <div>Anggaran</div><div>Rp xxx</div>
                </div>
            </section>

        </main>

    </div>
</div>

<script src="{{ asset('js/kegiatan.js') }}"></script>
<script src="{{ asset('js/LayoutPengusul.js') }}"></script>
@endsection