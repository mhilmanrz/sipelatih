@extends('layout.dashboard', ['title' => 'Tambah Data Kegiatan'])

@section('content')
<div class="container-fluid" style="max-width: 900px;">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-center bg-white border-bottom-0 pt-4">
            <h5 class="m-0 fw-bold text-teal" style="color: #007A7F;">Data Kegiatan</h5>
        </div>
        <div class="card-body px-5 pb-5">
            <form method="POST" action="#">
                @csrf

                <!-- Tahun -->
                <div class="row mb-3 align-items-center">
                    <label for="tahun" class="col-sm-3 col-form-label fw-semibold" style="color: #007A7F;">Tahun</label>
                    <div class="col-sm-9">
                        <input type="text" id="tahun" class="form-control rounded-pill border-2" style="border-color: #007A7F;">
                    </div>
                </div>

                <!-- No Surat -->
                <div class="row mb-3 align-items-center">
                    <label for="no_registrasi" class="col-sm-3 col-form-label fw-semibold" style="color: #007A7F;">No. Surat</label>
                    <div class="col-sm-9">
                        <input type="text" id="no_registrasi" class="form-control rounded-pill border-2" style="border-color: #007A7F;">
                    </div>
                </div>
                
                <!-- Nama Kegiatan -->
                <div class="row mb-3 align-items-center">
                    <label for="nama" class="col-sm-3 col-form-label fw-semibold" style="color: #007A7F;">Nama Kegiatan</label>
                    <div class="col-sm-9">
                        <select id="nama" class="form-select rounded-pill border-2" style="border-color: #007A7F;">
                            <option>- PILIH -</option>
                            <option>- Kegiatan 1 -</option>
                            <option>- Kegiatan 2 -</option>
                            <option>- Kegiatan 3 -</option>
                        </select>
                    </div>
                </div>

                <!-- Jenis Kegiatan -->
                <div class="row mb-3 align-items-center">
                    <label for="jenis_kegiatan" class="col-sm-3 col-form-label fw-semibold" style="color: #007A7F;">Jenis Kegiatan</label>
                    <div class="col-sm-9">
                        <select id="jenis_kegiatan" class="form-select rounded-pill border-2" style="border-color: #007A7F;">
                            <option>- PILIH -</option>
                            <option>- Pelatihan -</option>
                            <option>- Workshop -</option>
                            <option>- Webinar -</option>
                            <option>- Seminar -</option>
                        </select>
                    </div>
                </div>

                <!-- Cakupan -->
                <div class="row mb-3 align-items-center">
                    <label for="cakupan" class="col-sm-3 col-form-label fw-semibold" style="color: #007A7F;">Cakupan Kegiatan</label>
                    <div class="col-sm-9">
                        <select id="cakupan" class="form-select rounded-pill border-2" style="border-color: #007A7F;">
                            <option>- PILIH -</option>
                            <option>- Teknis -</option>
                            <option>- Manajerial -</option>
                            <option>- Sosiokultural -</option>
                        </select>
                    </div>
                </div>

                <!-- Jenis Materi -->
                <div class="row mb-3 align-items-center">
                    <label for="jenis_materi" class="col-sm-3 col-form-label fw-semibold" style="color: #007A7F;">Jenis Materi</label>
                    <div class="col-sm-9">
                        <select id="jenis_materi" class="form-select rounded-pill border-2" style="border-color: #007A7F;">
                            <option>- PILIH -</option>
                            <option>- Kurikulum -</option>
                            <option>- Non Kurikulum -</option>
                        </select>
                    </div>
                </div>

                <!-- Metode -->
                <div class="row mb-3 align-items-center">
                    <label for="metode" class="col-sm-3 col-form-label fw-semibold" style="color: #007A7F;">Metode</label>
                    <div class="col-sm-9">
                        <select id="metode" class="form-select rounded-pill border-2" style="border-color: #007A7F;">
                            <option>- PILIH -</option>
                            <option>- Blended -</option>
                            <option>- Luring -</option>
                            <option>- Daring -</option>
                        </select>
                    </div>
                </div>

                <!-- Angkatan -->
                <div class="row mb-3 align-items-center">
                    <label for="angkatan" class="col-sm-3 col-form-label fw-semibold" style="color: #007A7F;">Angkatan</label>
                    <div class="col-sm-9">
                        <select id="angkatan" class="form-select rounded-pill border-2" style="border-color: #007A7F;">
                            <option>- PILIH -</option>
                        </select>
                    </div>
                </div>

                <!-- Bentuk -->
                <div class="row mb-3 align-items-center">
                    <label for="bentuk" class="col-sm-3 col-form-label fw-semibold" style="color: #007A7F;">Bentuk</label>
                    <div class="col-sm-9">
                        <input type="text" id="bentuk" class="form-control rounded-pill border-2" style="border-color: #007A7F;">
                    </div>
                </div>

                <!-- Institusi Kerjasama -->
                <div class="row mb-3 align-items-center">
                    <label for="institusi_kerjasama" class="col-sm-3 col-form-label fw-semibold" style="color: #007A7F;">Institusi Kerjasama</label>
                    <div class="col-sm-9">
                        <input type="text" id="institusi_kerjasama" class="form-control rounded-pill border-2" style="border-color: #007A7F;">
                    </div>
                </div>

                <!-- Target Peserta -->
                <div class="row mb-3 align-items-center">
                    <label for="peserta" class="col-sm-3 col-form-label fw-semibold" style="color: #007A7F;">Target Peserta</label>
                    <div class="col-sm-9">
                        <input type="number" id="peserta" class="form-control rounded-pill border-2" style="border-color: #007A7F;">
                    </div>
                </div>

                <!-- Waktu Kegiatan -->
                <div class="row mb-3 align-items-center">
                    <label for="tgl_mulai" class="col-sm-3 col-form-label fw-semibold" style="color: #007A7F;">Waktu Kegiatan</label>
                    <div class="col-sm-9">
                        <div class="d-flex align-items-center gap-3">
                            <input type="date" id="tgl_mulai" class="form-control rounded-pill border-2" style="border-color: #007A7F;" placeholder="Mulai">
                            <span class="fw-semibold">s/d</span>
                            <input type="date" id="tgl_selesai" class="form-control rounded-pill border-2" style="border-color: #007A7F;" placeholder="Selesai">
                        </div>
                    </div>
                </div>

                <!-- Anggaran -->
                <div class="row mb-3 align-items-center">
                    <label for="anggaran" class="col-sm-3 col-form-label fw-semibold" style="color: #007A7F;">Anggaran</label>
                    <div class="col-sm-9">
                        <input type="number" id="anggaran" class="form-control rounded-pill border-2" style="border-color: #007A7F;">
                    </div>
                </div>

                <!-- Unit Pengusul -->
                <div class="row mb-3 align-items-center">
                    <label for="unit_pengusul" class="col-sm-3 col-form-label fw-semibold" style="color: #007A7F;">Unit Pengusul</label>
                    <div class="col-sm-9">
                        <input type="text" id="unit_pengusul" class="form-control rounded-pill border-2" style="border-color: #007A7F;">
                    </div>
                </div>

                <!-- Nama PIC -->
                <div class="row mb-3 align-items-center">
                    <label for="pic" class="col-sm-3 col-form-label fw-semibold" style="color: #007A7F;">Nama PIC</label>
                    <div class="col-sm-9">
                        <input type="text" id="pic" class="form-control rounded-pill border-2" style="border-color: #007A7F;">
                    </div>
                </div>

                <!-- WA PIC -->
                <div class="row mb-3 align-items-center">
                    <label for="wa_pic" class="col-sm-3 col-form-label fw-semibold" style="color: #007A7F;">WA PIC</label>
                    <div class="col-sm-9">
                        <input type="text" id="wa_pic" class="form-control rounded-pill border-2" style="border-color: #007A7F;">
                    </div>
                </div>

                <!-- Action -->
                <div class="row mt-5">
                    <div class="col-12 d-flex justify-content-center gap-3">
                        <button type="submit" class="btn btn-success rounded-pill px-5 fw-semibold shadow-sm" style="background-color: #22c55e; border-color: #22c55e;">
                            SIMPAN
                        </button>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary rounded-pill px-5 fw-semibold shadow-sm text-white">
                            BATAL
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
