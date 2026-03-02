@extends('layout.LayoutPengusul')

@section('title', 'Pengiriman')

@section('styles')
<link rel="stylesheet" href="{{ assets('css/LayoutPengusul.css') }}">
<link rel="stylesheet" href="{{ assets('css/pengiriman.css') }}">
@endsection

@section('content')

<div class="layout">

    <!-- OVERLAY -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- MAIN -->
    <div class="main">

        <!-- CONTENT -->
        <div class="content">

            <h2 class="title">PENGAJUAN DIKLAT</h2>

            <!-- INFO CARD -->
            <div class="info-card">
                <div class="info-grid">
                    <div><span>Nama Kegiatan</span> Workshop ICTEC</div>
                    <div><span>Pengusul</span> RSUPN Dr. Cipto Mangunkusumo</div>
                    <div><span>Jenis Kegiatan</span> Workshop</div>
                    <div><span>Cakupan</span> Nasional</div>
                    <div><span>Jenis Materi</span> Spesifik Keprofesian</div>
                    <div><span>Waktu Pelaksanaan</span> 1 Nov 2025 s/d 25 Nov 2025</div>
                    <div><span>Status</span> Draft</div>
                </div>
            </div>

            <!-- TABS -->
            <div class="tab-wrapper">
                <div class="tabs">
        <button onclick="window.location='{{ route('usulan.kegiatan') }}'">Kegiatan</button>
        <button onclick="window.location='{{ route('usulan.sasaran') }}'">Sasaran Profesi</button>
        <button onclick="window.location='{{ route('usulan.kak') }}'">KAK</button>
        <button onclick="window.location='{{ route('usulan.materi') }}'">Materi</button>
        <button onclick="window.location='{{ route('usulan.narasumber') }}'">Narasumber</button>
        <button onclick="window.location='{{ route('usulan.peserta') }}'">Peserta</button>
        <button onclick="window.location='{{ route('usulan.pengiriman') }}'" class="active">Pengiriman</button>
        <button onclick="window.location='{{ route('usulan.penilaian') }}'">Penilaian</button>
        <button onclick="window.location='{{ route('usulan.sertifikat') }}'">Sertifikat</button>
                </div>

                <div class="tab-content">
                    <button class="btn-kirim" id="btnKirim">✈ Kirim</button>
                </div>
            </div>

        </div>

    </div>
</div>

<!-- MODAL -->
<div class="modal-overlay" id="modalKirim">
    <div class="modal-box">
        <button class="modal-close" id="closeModal">&times;</button>

        <p class="modal-text">
            Pastikan data yang Anda masukan sudah benar.
            Jika sudah benar klik <strong>KIRIM</strong>?
        </p>

        <div class="modal-actions">
            <button class="btn-confirm">✈ KIRIM</button>
            <button class="btn-cancel" id="btnBatal">BATAL</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ assets('js/LayoutPengusul.js') }}"></script>
<script src="{{ assets('js/pengiriman.js') }}"></script>
@endsection