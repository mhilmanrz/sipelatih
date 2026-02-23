@extends('layout.LayoutPengusul')

@section('title', 'Materi')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/materi.css') }}">
<link rel="stylesheet" href="{{ asset('css/LayoutPengusul.css') }}">
@endpush

@section('content')

<div class="content">

    <div class="page-title">PENGAJUAN DIKLAT</div>

    <!-- INFO CARD -->
    <div class="info-card">
        <div class="info-grid">
            <div>Nama Kegiatan</div><span>: Workshop ICTEC</span>
            <div>Pengusul</div><span>: RSUPN Dr. Cipto Mangunkusumo</span>
            <div>Jenis Kegiatan</div><span>: Workshop</span>
            <div>Cakupan</div><span>: Nasional</span>
            <div>Jenis Materi</div><span>: Spesifik Keprofesian</span>
            <div>Waktu Pelaksanaan</div><span>: 1 Nov 2025 s/d 25 Nov 2025</span>
            <div>Status</div><span>: Draft</span>
        </div>
    </div>

    <!-- TAB MENU -->
    <div class="tab-menu">
        <button onclick="window.location='{{ route('usulan.kegiatan') }}'">Kegiatan</button>
        <button onclick="window.location='{{ route('usulan.sasaran') }}'">Sasaran Profesi</button>
        <button onclick="window.location='{{ route('usulan.kak') }}'">KAK</button>
        <button onclick="window.location='{{ route('usulan.materi') }}'" class="active">Materi</button>
        <button onclick="window.location='{{ route('usulan.narasumber') }}'">Narasumber</button>
        <button onclick="window.location='{{ route('usulan.peserta') }}'">Peserta</button>
        <button onclick="window.location='{{ route('usulan.pengiriman') }}'">Pengiriman</button>
        <button onclick="window.location='{{ route('usulan.penilaian') }}'">Penilaian</button>
        <button onclick="window.location='{{ route('usulan.sertifikat') }}'">Sertifikat</button>
    </div>

    <!-- CONTENT CARD -->
    <div class="content-card">

        <div class="top-action">
            <button class="btn-add" id="openModal">+ Tambah</button>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Materi</th>
                        <th>JPL (45 menit)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    {{-- Data akan diisi via JS / Loop --}}
                </tbody>
            </table>
        </div>

    </div>

</div>

<!-- MODAL -->
<div class="modal" id="modal">
    <div class="modal-box">
        <button class="close-btn" id="closeModal">✕</button>
        <h3>Tambah Materi</h3>

        <div class="form-group">
            <label>Materi</label>
            <input type="text" id="materiInput">
        </div>

        <div class="form-group">
            <label>JPL (45 menit)</label>
            <input type="number" id="jplInput" step="0.1">
        </div>

        <div class="modal-buttons">
            <button class="btn-save" id="saveBtn">Simpan</button>
            <button class="btn-cancel" id="cancelBtn">Batal</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/materi.js') }}"></script>
<script src="{{ asset('js/LayoutPengusul.js') }}"></script>
@endpush