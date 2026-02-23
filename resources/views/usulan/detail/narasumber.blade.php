@extends('layout.LayoutPengusul')

@section('title', 'Narasumber')

@section('styles')
<link rel="stylesheet" href="{{ assets('css/LayoutPengusul.css') }}">
<link rel="stylesheet" href="{{ assets('css/narasumber.css') }}">
@endsection

@section('content')

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
    <div class="tabs">
        <button onclick="window.location='{{ route('usulan.kegiatan') }}'">Kegiatan</button>
        <button onclick="window.location='{{ route('usulan.sasaran') }}'">Sasaran Profesi</button>
        <button onclick="window.location='{{ route('usulan.kak') }}'">KAK</button>
        <button onclick="window.location='{{ route('usulan.materi') }}'">Materi</button>
        <button onclick="window.location='{{ route('usulan.narasumber') }}'" class="active">Narasumber</button>
        <button onclick="window.location='{{ route('usulan.peserta') }}'">Peserta</button>
        <button onclick="window.location='{{ route('usulan.pengiriman') }}'">Pengiriman</button>
        <button onclick="window.location='{{ route('usulan.penilaian') }}'">Penilaian</button>
        <button onclick="window.location='{{ route('usulan.sertifikat') }}'">Sertifikat</button>
    </div>

    <!-- CARD -->
    <div class="card">

        <div class="card-header">
            Narasumber / Pembicara
        </div>

        <!-- FORM -->
        <div class="form-section">

            <div class="form-group">
                <label>*Nama SDM</label>
                <select id="namaSDM">
                    <option value="">- PILIH NARASUMBER -</option>
                    <option>dr. Rabbinu Rangga</option>
                    <option>dr. Siti Aisyah</option>
                    <option>Ns. Ahmad Fauzi</option>
                </select>
            </div>

            <div class="form-group">
                <label>*Materi Ajar</label>
                <select id="materiAjar">
                    <option value="">- PILIH MATERI -</option>
                    <option>Pelatihan memasang infus</option>
                    <option>Bantuan Hidup Dasar</option>
                    <option>Aseptic Dispensing</option>
                </select>
            </div>

            <button onclick="simpanNarasumber()" class="btn-primary">
                💾 SIMPAN
            </button>

        </div>

        <!-- TABLE -->
        <div class="table-wrapper">
            <table id="narasumberTable">
                <thead>
                    <tr>
                        <th>NO.</th>
                        <th>Nama Narasumber</th>
                        <th>Materi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>dr. Rabbinu Rangga</td>
                        <td>Pelatihan memasang infus</td>
                        <td>
                            <button onclick="hapusRow(this)" class="btn-danger">
                                HAPUS
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- MODERATOR SECTION -->
        <div class="moderator-title">
            Moderator (Jika Ada)
        </div>

        <div class="form-section">

            <div class="form-group">
                <label>*Nama Moderator</label>
                <select id="namaModerator">
                    <option value="">- PILIH MODERATOR -</option>
                    <option>Rangga Moela</option>
                    <option>Ilham Fauzi</option>
                    <option>Andi Setiawan</option>
                </select>
            </div>

            <div class="form-group">
                <label>*Materi Ajar</label>
                <select id="materiModerator">
                    <option value="">- PILIH MATERI -</option>
                    <option>Resertifikasi Dispensing Aseptik</option>
                    <option>Bantuan Hidup Dasar</option>
                </select>
            </div>

            <button class="btn-primary" id="btnSimpanModerator">
                💾 SIMPAN
            </button>

        </div>

        <!-- TABLE MODERATOR -->
        <div class="table-wrapper">
            <table id="moderatorTable">
                <thead>
                    <tr>
                        <th>NO.</th>
                        <th>NIP</th>
                        <th>Nama Narasumber</th>
                        <th>Materi</th>
                        <th>Unit Kerja</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

    </div>
</div>

@endsection

@section('scripts')
<script src="{{ assets('js/LayoutPengusul.js') }}"></script>
<script src="{{ assets('js/narasumber.js') }}"></script>
@endsection