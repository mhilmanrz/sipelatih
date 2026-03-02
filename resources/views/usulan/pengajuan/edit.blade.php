@extends('layout.dashboard', ['title' => 'Edit Data Kegiatan'])

@section('content')
<div class="container-fluid" style="max-width: 900px;">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-center bg-white border-bottom-0 pt-4">
            <h5 class="m-0 fw-bold text-teal" style="color: #007A7F;">Edit Data Kegiatan</h5>
        </div>
        <div class="card-body px-5 pb-5">
            <form method="POST" action="{{ route('kegiatan.update', $kegiatan->id ?? 1) }}">
                @csrf
                @method('PUT')

                <!-- Tanggal -->
                <div class="row mb-3 align-items-center">
                    <label for="tahun" class="col-sm-3 col-form-label fw-semibold" style="color: #007A7F;">Tahun</label>
                    <div class="col-sm-9">
                        <input type="text" id="tahun" name="tahun" value="{{ old('tahun', $kegiatan->tahun ?? '') }}" class="form-control rounded-pill border-2" style="border-color: #007A7F;">
                    </div>
                </div>

                <!-- No Surat -->
                <div class="row mb-3 align-items-center">
                    <label for="no_registrasi" class="col-sm-3 col-form-label fw-semibold" style="color: #007A7F;">No. Surat</label>
                    <div class="col-sm-9">
                        <input type="text" id="no_registrasi" name="no_registrasi" value="{{ old('no_registrasi', $kegiatan->no_registrasi ?? '') }}" class="form-control rounded-pill border-2" style="border-color: #007A7F;">
                    </div>
                </div>
                
                <!-- Nama Kegiatan -->
                <div class="row mb-3 align-items-center">
                    <label for="nama" class="col-sm-3 col-form-label fw-semibold" style="color: #007A7F;">Nama Kegiatan</label>
                    <div class="col-sm-9">
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $kegiatan->nama ?? '') }}" class="form-control rounded-pill border-2" style="border-color: #007A7F;">
                    </div>
                </div>

                <!-- Jenis Kegiatan -->
                <div class="row mb-3 align-items-center">
                    <label for="jenis_kegiatan" class="col-sm-3 col-form-label fw-semibold" style="color: #007A7F;">Jenis Kegiatan</label>
                    <div class="col-sm-9">
                        <input type="text" id="jenis_kegiatan" name="jenis_kegiatan" value="{{ old('jenis_kegiatan', $kegiatan->jenis_kegiatan ?? '') }}" class="form-control rounded-pill border-2" style="border-color: #007A7F;">
                    </div>
                </div>

                <!-- Cakupan -->
                <div class="row mb-3 align-items-center">
                    <label for="cakupan" class="col-sm-3 col-form-label fw-semibold" style="color: #007A7F;">Cakupan Kegiatan</label>
                    <div class="col-sm-9">
                        <input type="text" id="cakupan" name="cakupan" value="{{ old('cakupan', $kegiatan->cakupan ?? '') }}" class="form-control rounded-pill border-2" style="border-color: #007A7F;">
                    </div>
                </div>

                <!-- Jenis Materi -->
                <div class="row mb-3 align-items-center">
                    <label for="jenis_materi" class="col-sm-3 col-form-label fw-semibold" style="color: #007A7F;">Jenis Materi</label>
                    <div class="col-sm-9">
                        <input type="text" id="jenis_materi" name="jenis_materi" value="{{ old('jenis_materi', $kegiatan->jenis_materi ?? '') }}" class="form-control rounded-pill border-2" style="border-color: #007A7F;">
                    </div>
                </div>

                <!-- Metode -->
                <div class="row mb-3 align-items-center">
                    <label for="metode" class="col-sm-3 col-form-label fw-semibold" style="color: #007A7F;">Metode</label>
                    <div class="col-sm-9">
                        <input type="text" id="metode" name="metode" value="{{ old('metode', $kegiatan->metode ?? '') }}" class="form-control rounded-pill border-2" style="border-color: #007A7F;">
                    </div>
                </div>

                <!-- Angkatan -->
                <div class="row mb-3 align-items-center">
                    <label for="angkatan" class="col-sm-3 col-form-label fw-semibold" style="color: #007A7F;">Angkatan</label>
                    <div class="col-sm-9">
                        <input type="number" id="angkatan" name="angkatan" value="{{ old('angkatan', $kegiatan->angkatan ?? '') }}" class="form-control rounded-pill border-2" style="border-color: #007A7F;">
                    </div>
                </div>

                <!-- Bentuk -->
                <div class="row mb-3 align-items-center">
                    <label for="bentuk" class="col-sm-3 col-form-label fw-semibold" style="color: #007A7F;">Bentuk</label>
                    <div class="col-sm-9">
                        <input type="text" id="bentuk" name="bentuk" value="{{ old('bentuk', $kegiatan->bentuk ?? '') }}" class="form-control rounded-pill border-2" style="border-color: #007A7F;">
                    </div>
                </div>

                <!-- Institusi Kerjasama -->
                <div class="row mb-3 align-items-center">
                    <label for="institusi_kerjasama" class="col-sm-3 col-form-label fw-semibold" style="color: #007A7F;">Institusi Kerjasama</label>
                    <div class="col-sm-9">
                        <input type="text" id="institusi_kerjasama" name="institusi_kerjasama" value="{{ old('institusi_kerjasama', $kegiatan->institusi_kerjasama ?? '') }}" class="form-control rounded-pill border-2" style="border-color: #007A7F;">
                    </div>
                </div>

                <!-- Target Peserta -->
                <div class="row mb-3 align-items-center">
                    <label for="peserta" class="col-sm-3 col-form-label fw-semibold" style="color: #007A7F;">Jumlah Peserta</label>
                    <div class="col-sm-9">
                        <input type="number" id="peserta" name="peserta" value="{{ old('peserta', $kegiatan->peserta ?? '') }}" class="form-control rounded-pill border-2" style="border-color: #007A7F;">
                    </div>
                </div>

                <!-- Waktu Kegiatan -->
                <div class="row mb-3 align-items-center">
                    <label for="tgl_mulai" class="col-sm-3 col-form-label fw-semibold" style="color: #007A7F;">Waktu Kegiatan</label>
                    <div class="col-sm-9">
                        <div class="d-flex align-items-center gap-3">
                            <input type="date" id="tgl_mulai" name="tgl_mulai" class="form-control rounded-pill border-2" style="border-color: #007A7F;" value="{{ old('tgl_mulai', $kegiatan->tgl_mulai ?? '') }}" placeholder="Mulai">
                            <span class="fw-semibold">s/d</span>
                            <input type="date" id="tgl_selesai" name="tgl_selesai" class="form-control rounded-pill border-2" style="border-color: #007A7F;" value="{{ old('tgl_selesai', $kegiatan->tgl_selesai ?? '') }}" placeholder="Selesai">
                        </div>
                    </div>
                </div>

                <!-- Anggaran -->
                <div class="row mb-3 align-items-center">
                    <label for="anggaran" class="col-sm-3 col-form-label fw-semibold" style="color: #007A7F;">Anggaran</label>
                    <div class="col-sm-9">
                        <input type="number" id="anggaran" name="anggaran" value="{{ old('anggaran', $kegiatan->anggaran ?? '') }}" class="form-control rounded-pill border-2" style="border-color: #007A7F;">
                    </div>
                </div>

                <!-- Unit Pengusul -->
                <div class="row mb-3 align-items-center">
                    <label for="unit_pengusul" class="col-sm-3 col-form-label fw-semibold" style="color: #007A7F;">Unit Pengusul</label>
                    <div class="col-sm-9">
                        <input type="text" id="unit_pengusul" name="unit_pengusul" value="{{ old('unit_pengusul', $kegiatan->unit_pengusul ?? '') }}" class="form-control rounded-pill border-2" style="border-color: #007A7F;">
                    </div>
                </div>

                <!-- Nama PIC -->
                <div class="row mb-3 align-items-center">
                    <label for="pic" class="col-sm-3 col-form-label fw-semibold" style="color: #007A7F;">Nama PIC</label>
                    <div class="col-sm-9">
                        <input type="text" id="pic" name="pic" value="{{ old('pic', $kegiatan->pic ?? '') }}" class="form-control rounded-pill border-2" style="border-color: #007A7F;">
                    </div>
                </div>

                <!-- WA PIC -->
                <div class="row mb-3 align-items-center">
                    <label for="wa_pic" class="col-sm-3 col-form-label fw-semibold" style="color: #007A7F;">WA PIC</label>
                    <div class="col-sm-9">
                        <input type="text" id="wa_pic" name="wa_pic" value="{{ old('wa_pic', $kegiatan->wa_pic ?? '') }}" class="form-control rounded-pill border-2" style="border-color: #007A7F;">
                    </div>
                </div>

                <!-- Action -->
                <div class="row mt-5">
                    <div class="col-12 d-flex justify-content-center gap-3">
                        <button type="submit" class="btn btn-success rounded-pill px-5 fw-semibold shadow-sm" style="background-color: #22c55e; border-color: #22c55e;">
                            SIMPAN
                        </button>
                        <a href="{{ route('kegiatan.index') }}" class="btn btn-secondary rounded-pill px-5 fw-semibold shadow-sm text-white">
                            BATAL
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
