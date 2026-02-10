@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-8">
    <div class="bg-white border-2 border-teal-500 rounded-xl p-8">
        <h2 class="text-center text-lg font-semibold text-[#405A5B] mb-8">
            Edit Data Kegiatan
        </h2>

        <form
            method="POST"
            action="{{ route('kegiatan.update', $kegiatan->id) }}"
            class="space-y-4">

            @csrf
            @method('PUT')

            @php
                $input = 'w-full rounded-full border-2 border-teal-500 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-teal-400';
                $label = 'text-sm font-semibold text-[#007A7F]';
            @endphp

            <!-- Tanggal -->
            <div class="grid grid-cols-3 gap-4 items-center">
                <label for="tahun" class="{{ $label }} col-span-1">Tahun</label>
                <input type="text" id="tahun" name="tahun" value="{{ old('tahun', $kegiatan->tahun ?? '') }}" class="{{ $input }} col-span-2">
            </div>

            <!-- No Surat -->
            <div class="grid grid-cols-3 gap-4 items-center">
                <label for="no_registrasi" class="{{ $label }}">No. Surat</label>
                <input
                    type="text"
                    id="no_registrasi"
                    name="no_registrasi"
                    value="{{ old('no_registrasi', $kegiatan->no_registrasi ?? '') }}"
                    class="{{ $input }} col-span-2"
                >
            </div>

            <!-- Nama Kegiatan -->
            <div class="grid grid-cols-3 gap-4 items-center">
                <label for="nama" class="{{ $label }}">Nama Kegiatan</label>
                <input
                    type="text"
                    id="nama"
                    name="nama"
                    value="{{ old('nama', $kegiatan->nama) }}"
                    class="{{ $input }} col-span-2"
                >
            </div>

            <!-- Jenis Kegiatan -->
            <div class="grid grid-cols-3 gap-4 items-center">
                <label for="jenis_kegiatan" class="{{ $label }}">Jenis Kegiatan</label>
                <input
                    type="text"
                    id="jenis_kegiatan"
                    name="jenis_kegiatan"
                    value="{{ old('jenis_kegiatan', $kegiatan->jenis_kegiatan) }}"
                    class="{{ $input }} col-span-2"
                >
            </div>

            <!-- Cakupan -->
            <div class="grid grid-cols-3 gap-4 items-center">
                <label for="cakupan" class="{{ $label }}">Cakupan Kegiatan</label>
                <input
                    type="text"
                    id="cakupan"
                    name="cakupan"
                    value="{{ old('cakupan', $kegiatan->cakupan) }}"
                    class="{{ $input }} col-span-2"
                >
            </div>

            <!-- Jenis Materi -->
            <div class="grid grid-cols-3 gap-4 items-center">
                <label for="jenis_materi" class="{{ $label }}">Jenis Materi</label>
                <input
                    type="text"
                    id="jenis_materi"
                    name="jenis_materi"
                    value="{{ old('jenis_materi', $kegiatan->jenis_materi) }}"
                    class="{{ $input }} col-span-2"
                >
            </div>

            <!-- Metode -->
            <div class="grid grid-cols-3 gap-4 items-center">
                <label for="metode" class="{{ $label }}">Metode</label>
                <input
                    type="text"
                    id="metode"
                    name="metode"
                    value="{{ old('metode', $kegiatan->metode) }}"
                    class="{{ $input }} col-span-2"
                >
            </div>

            <!-- Angkatan -->
            <div class="grid grid-cols-3 gap-4 items-center">
                <label for="angkatan" class="{{ $label }}">Angkatan</label>
                <input
                    type="number"
                    id="angkatan"
                    name="angkatan"
                    value="{{ old('angkatan', $kegiatan->angkatan) }}"
                    class="{{ $input }} col-span-2"
                >
            </div>

            <!-- Bentuk -->
            <div class="grid grid-cols-3 gap-4 items-center">
                <label for="bentuk" class="{{ $label }}">Bentuk</label>
                <input
                    type="text"
                    id="bentuk"
                    name="bentuk"
                    value="{{ old('bentuk', $kegiatan->bentuk) }}"
                    class="{{ $input }} col-span-2"
                >
            </div>

            <!-- Institusi -->
            <div class="grid grid-cols-3 gap-4 items-center">
                <label for="institusi_kerjasama" class="{{ $label }}">Institusi Kerjasama</label>
                <input
                    type="text"
                    id="institusi_kerjasama"
                    name="institusi_kerjasama"
                    value="{{ old('institusi_kerjasama', $kegiatan->institusi_kerjasama) }}"
                    class="{{ $input }} col-span-2"
                >
            </div>

            <!-- Peserta -->
            <div class="grid grid-cols-3 gap-4 items-center">
                <label for="peserta" class="{{ $label }}">Jumlah Peserta</label>
                <input
                    type="number"
                    id="peserta"
                    name="peserta"
                    value="{{ old('peserta', $kegiatan->peserta) }}"
                    class="{{ $input }} col-span-2"
                >
            </div>

            <!-- Waktu -->
                <div class="grid grid-cols-3 gap-4 items-center">
                <label for="tgl_mulai" class="{{ $label }}">Mulai</label>
                <div class="col-span-2 flex gap-4">
                <input type="date" id="tgl_mulai" name="tgl_mulai" class="{{ $input }}" value="{{ old('tgl_mulai', $kegiatan->tgl_mulai) }}">
                <label for="tgl_selesai" class="{{ $label }}">Selesai</label>
                <input type="date" id="tgl_selesai" class="{{ $input }}" placeholder="Selesai">
                </div>
            </div>

            {{-- <div class="grid grid-cols-3 gap-4 items-center">
                <label for="" class="{{ $label }}">Waktu Kegiatan</label>
                <div class="col-span-2 flex gap-4">
                    <input
                        type="date"
                        name="tgl_mulai"
                        value="{{ old('tgl_mulai', $kegiatan->tgl_mulai) }}"
                        class="{{ $input }}"
                    >
                    <input
                        type="date"
                        name="tgl_selesai"
                        value="{{ old('tgl_selesai', $kegiatan->tgl_selesai) }}"
                        class="{{ $input }}"
                    >
                </div>
            </div> --}}

            <!-- Anggaran -->
            <div class="grid grid-cols-3 gap-4 items-center">
                <label for="anggaran" class="{{ $label }}">Anggaran</label>
                <input
                    type="number"
                    id="anggaran"
                    name="anggaran"
                    value="{{ old('anggaran', $kegiatan->anggaran) }}"
                    class="{{ $input }} col-span-2"
                >
            </div>

            <!-- Unit -->
            <div class="grid grid-cols-3 gap-4 items-center">
                <label for="unit_pengusul" class="{{ $label }}">Unit Pengusul</label>
                <input
                    type="text"
                    id="unit_pengusul"
                    name="unit_pengusul"
                    value="{{ old('unit_pengusul', $kegiatan->unit_pengusul) }}"
                    class="{{ $input }} col-span-2"
                >
            </div>

            <!-- PIC -->
            <div class="grid grid-cols-3 gap-4 items-center">
                <label for="pic" class="{{ $label }}">Nama PIC</label>
                <input
                    type="text"
                    id="pic"
                    name="pic"
                    value="{{ old('pic', $kegiatan->pic) }}"
                    class="{{ $input }} col-span-2"
                >
            </div>

            <!-- WA PIC -->
            <div class="grid grid-cols-3 gap-4 items-center">
                <label for="wa_pic" class="{{ $label }}">WA PIC</label>
                <input
                    type="text"
                    id="wa_pic"
                    name="wa_pic"
                    value="{{ old('wa_pic', $kegiatan->wa_pic) }}"
                    class="{{ $input }} col-span-2"
                >
            </div>

            <!-- Action -->
            <div class="flex justify-center gap-4 pt-6">
                <button
                    type="submit"
                    class="bg-green-500 text-white px-6 py-2 rounded-full hover:bg-green-600"
                >
                    SIMPAN
                </button>

                <a
                    href="{{ route('kegiatan.index') }}"
                    class="bg-gray-400 text-white px-6 py-2 rounded-full hover:bg-gray-500"
                >
                    BATAL
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
