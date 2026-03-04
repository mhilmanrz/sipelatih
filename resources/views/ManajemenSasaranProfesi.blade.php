@extends('layout.LayoutSuperAdmin')

@section('title','Sasaran Profesi')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/ManajemenSasaranProfesi.css') }}">
@endpush

@section('content')

<main class="content input-page">

    <!-- TOPBAR -->
    <div class="topbar">
        <div class="menu-icon">
            <i class="fas fa-bars"></i>
        </div>
        <div class="user">
            <i class="far fa-bell"></i>
            <span><i class="far fa-user"></i> Super Admin Diklat</span>
        </div>
    </div>

    <h1>Sasaran Profesi</h1>

    <div class="table-section">

        <button class="btn-tambah" id="openModal">
            <i class="fas fa-plus"></i> Tambah
        </button>

        <table>
            <thead>
                <tr>
                    <th>NO.</th>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Profesi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>367205520538705121</td>
                    <td>Ilham Fauzie</td>
                    <td>Perawat Vokasi</td>
                    <td>
                        <button class="hapus">HAPUS</button>
                        <button class="update">UPDATE</button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>367205520538705121</td>
                    <td>Handry Tanubrata</td>
                    <td>Ners Spesialis Keperawatan Jiwa</td>
                    <td>
                        <button class="hapus">HAPUS</button>
                        <button class="update">UPDATE</button>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>

</main>

<!-- MODAL -->
<div class="modal" id="modal">
    <div class="modal-content">
        <span class="close" id="closeModal">&times;</span>

        <div class="form-group">
            <label>NIK</label>
            <input type="text" name="nik">
        </div>

        <div class="form-group">
            <label>Nama</label>
            <select name="nama">
                <option>-PILIH-</option>
            </select>
        </div>

        <div class="form-group">
            <label>Sasaran Profesi</label>
            <select name="profesi">
                <option>-PILIH-</option>
            </select>
        </div>

        <div class="modal-buttons">
            <button class="simpan">SIMPAN</button>
            <button class="batal" id="cancelBtn">BATAL</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/ManajemenSasaranProfesi.js') }}"></script>
@endpush