@extends('layout.LayoutPengusul')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/kak.css') }}">
@endpush

@section('content')

<div class="layout">

    <div class="content">

        <div class="main-bg">

            <h1 class="page-title">
                PENGAJUAN DIKLAT
            </h1>

            <!-- INFO CARD -->
            <div class="info-card">
                <div class="info-grid">
                    <div>Nama Kegiatan</div><div>: Workshop ICTEC</div>
                    <div>Pengusul</div><div>: RSUPN Dr. Cipto Mangunkusumo</div>
                    <div>Jenis Kegiatan</div><div>: Workshop</div>
                    <div>Cakupan</div><div>: Nasional</div>
                    <div>Jenis Materi</div><div>: Spesifik</div>
                    <div>Waktu Pelaksanaan</div><div>: 1 Nov 2025 s/d 25 Nov 2025</div>
                    <div>Status</div><div>: Draft</div>
                </div>
            </div>

            <!-- TAB -->
            <div class="tab-header">
        <button onclick="window.location='{{ route('usulan.kegiatan') }}'" class="tab">Kegiatan</button>
        <button onclick="window.location='{{ route('usulan.sasaran') }}'" class="tab">Sasaran Profesi</button>
        <button onclick="window.location='{{ route('usulan.kak') }}'" class="tab active">KAK</button>
        <button onclick="window.location='{{ route('usulan.materi') }}'" class="tab">Materi</button>
        <button onclick="window.location='{{ route('usulan.narasumber') }}'" class="tab">Narasumber</button>
        <button onclick="window.location='{{ route('usulan.peserta') }}'" class="tab">Peserta</button>
        <button onclick="window.location='{{ route('usulan.pengiriman') }}'">Pengiriman</button>
        <button onclick="window.location='{{ route('usulan.penilaian') }}'">Penilaian</button>
        <button onclick="window.location='{{ route('usulan.sertifikat') }}'">Sertifikat</button>
            </div>

            <!-- CONTENT CARD -->
            <div class="content-card">

                <div class="action-buttons">
                    <button onclick="openModal()" class="btn-edit">
                        Edit
                    </button>

                    <button class="btn-download">
                        Unduh Format KAK
                    </button>
                </div>

                <div class="pdf-container">
                    <iframe src="{{ asset('sample.pdf') }}"></iframe>
                </div>

            </div>

        </div>
    </div>
</div>

<!-- MODAL -->
<div id="modal" class="modal-overlay">
    <div class="modal-box">

        <button onclick="closeModal()" class="close-btn">
            ✖
        </button>

        <h2>Edit KAK</h2>

        <div class="upload-box">
            ⬆
            <p>Unggah File</p>
        </div>

        <input type="file">

        <div class="modal-buttons">
            <button onclick="saveFile()" class="btn-save">
                SIMPAN
            </button>

            <button onclick="closeModal()" class="btn-cancel">
                BATAL
            </button>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/kak.js') }}"></script>
@endpush