@extends('layout.LayoutPengusul')

@section('title','Sasaran Profesi')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/sasaranprofesi.css') }}">
@endpush

@section('content')

<div class="main">

    <section class="content">

        <!-- INFO CARD -->
        <div class="card info-card">
            <div class="grid">
                <div class="label">Nama Kegiatan</div>
                <div>: Workshop ICTEC</div>

                <div class="label">Pengusul</div>
                <div>: RSUPN Dr. Cipto Mangunkusumo</div>

                <div class="label">Jenis Kegiatan</div>
                <div>: Workshop</div>

                <div class="label">Cakupan</div>
                <div>: Nasional</div>

                <div class="label">Jenis Materi</div>
                <div>: Spesifik Keprofesian</div>

                <div class="label">Waktu Pelaksanaan</div>
                <div>: 1 November 2025 s/d 25 November 2025</div>

                <div class="label">Status</div>
                <div>: Draft</div>
            </div>
        </div>

        <!-- TABLE CARD -->
        <div class="card">

            <div class="tabs">
        <button onclick="window.location='{{ route('usulan.kegiatan') }}'" class="tab">Kegiatan</button>
        <button onclick="window.location='{{ route('usulan.sasaran') }}'" class="tab active">Sasaran Profesi</button>
        <button onclick="window.location='{{ route('usulan.kak') }}'" class="tab">KAK</button>
        <button onclick="window.location='{{ route('usulan.materi') }}'"class="tab">Materi</button>
        <button onclick="window.location='{{ route('usulan.narasumber') }}'" class="tab">Narasumber</button>
        <button onclick="window.location='{{ route('usulan.peserta') }}'" class="tab">Peserta</button>
        <button onclick="window.location='{{ route('usulan.pengiriman') }}'">Pengiriman</button>
        <button onclick="window.location='{{ route('usulan.penilaian') }}'">Penilaian</button>
        <button onclick="window.location='{{ route('usulan.sertifikat') }}'">Sertifikat</button>
            </div>

            <button id="openModal" class="btn-primary">+ Tambah</button>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th width="60">NO.</th>
                            <th>Profesi</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="profesiTableBody">
                        <tr>
                            <td>1</td>
                            <td>Perawat Vokasi</td>
                            <td>
                                <button class="btn-delete">HAPUS</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

    </section>

</div>

<!-- MODAL -->
<div id="modal" class="modal">
    <div class="modal-content">

        <button id="closeModal" class="close-btn">✕</button>

        <div class="form-group">
            <label>*Sasaran Profesi</label>
            <select id="profesiSelect">
                <option value="">-PILIH-</option>
                <option>Perawat Vokasi</option>
                <option>Ners Spesialis Keperawatan Jiwa</option>
                <option>Ners Spesialis Keperawatan Anak</option>
            </select>
        </div>

        <div class="modal-buttons">
            <button id="saveBtn" class="btn-save">SIMPAN</button>
            <button id="cancelBtn" class="btn-cancel">BATAL</button>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/sasaranprofesi.js') }}"></script>
<script src="{{ asset('js/LayoutPengusul.js') }}"></script>
@endpush