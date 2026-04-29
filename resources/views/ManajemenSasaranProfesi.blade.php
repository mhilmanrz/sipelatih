@extends('layout.LayoutSuperAdmin')

@section('title','Sasaran Profesi')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/ManajemenSasaranProfesi.css') }}">
@endpush

@section('content')

<main class="input-page">
    <x-page-title>Sasaran Profesi</x-page-title>

    <div class="table-section">

        <button class="btn-tambah" id="openModal">
            <i class="fas fa-plus"></i> Tambah
        </button>

        <table>
            <thead class="bg-[#007a7a] border border-white py-3 px-4 font-semibold">
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
                        <button  style="background-color: #ef4444;" class="text-white px-3 py-1.5 rounded hover:bg-[#dc2626] text-sm font-semibold transition inline-block">Hapus</button>
                        <button class="update">UPDATE</button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>367205520538705121</td>
                    <td>Handry Tanubrata</td>
                    <td>Ners Spesialis Keperawatan Jiwa</td>
                    <td>
                        <button  style="background-color: #ef4444;" class="text-white px-3 py-1.5 rounded hover:bg-[#dc2626] text-sm font-semibold transition inline-block">Hapus</button>
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