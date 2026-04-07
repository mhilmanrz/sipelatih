@extends('layout.LayoutSuperAdmin')

@section('title', 'Tambah Data Kegiatan')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/LayoutSuperAdmin.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/tambahdata.css') }}">
@endpush

@section('content')
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
            <select>
                <option>-PILIH-</option>
            </select>
        </div>

        <div class="form-row">
            <label>Jenis Kegiatan</label>
            <select>
                <option>-PILIH-</option>
            </select>
        </div>

        <div class="form-row">
            <label>Cakupan Kegiatan</label>
            <select>
                <option>-PILIH-</option>
            </select>
        </div>

        <div class="form-row">
            <label>Jenis Materi</label>
            <select>
                <option>-PILIH-</option>
            </select>
        </div>

        <div class="form-row">
            <label>Metode</label>
            <select>
                <option>-PILIH-</option>
            </select>
        </div>

        <div class="form-row">
            <label>Angkatan</label>
            <select id="angka">
                <option>-PILIH-</option>
            </select>
        </div>

        <div class="form-row">
            <label>Bentuk</label>
            <select>
                <option>-PILIH-</option>
            </select>
        </div>

        <div class="form-row">
            <label>Institusi Kerjasama</label>
            <input type="text">
        </div>

        <div class="form-row">
            <label>Target Peserta</label>
            <select>
                <option>-PILIH-</option>
            </select>
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
@endsection

@push('scripts')
<script src="{{ asset('assets/js/LayoutSuperAdmin.js') }}"></script>
<script src="{{ asset('assets/js/tambahdata.js') }}"></script>
@endpush