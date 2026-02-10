@extends('layout.app')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-8">
    <div class="bg-white border-2 border-teal-500 rounded-xl p-8">
        <h2 class="text-center text-lg font-bold text-[#405A5B] mb-8">
            Data Kegiatan
        </h2>

        <form method="POST" action="#" class="space-y-4">
            @csrf

            {{-- Helper class --}}
            @php
                $input = 'w-full rounded-full border-3 border-teal-500 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-teal-400';
                $select = $input;
                $label = 'text-sm font-semibold text-[#007A7F]';
            @endphp

            <!-- Tanggal / Bulan / Tahun -->
            <div class="grid grid-cols-3 gap-4 items-center">
                <label for="tahun" class="{{ $label }} col-span-1">Tahun</label>
                <input type="text" id="tahun" class="{{ $input }} col-span-2">
            </div>

            <!-- No Surat -->
            <div class="grid grid-cols-3 gap-4 items-center">
                <label for="no_registrasi" class="{{ $label }}">No. Surat</label>
                <input type="text" id="no_registrasi" class="{{ $input }} col-span-2">
            </div>

            <!-- Nama Kegiatan -->
            <div class="grid grid-cols-3 gap-4 items-center">
                <label for="nama" class="{{ $label }}">Nama Kegiatan</label>
                <select id="nama" class="{{ $select }} col-span-2">
                    <option>- PILIH -</option>
                    <option>- Kegiatan 1 -</option>
                    <option>- Kegiatan 2 -</option>
                    <option>- Kegiatan 3 -</option>
                </select>
            </div>

            <!-- Jenis Kegiatan -->
            <div class="grid grid-cols-3 gap-4 items-center">
                <label for="jenis_kegiatan" class="{{ $label }}">Jenis Kegiatan</label>
                <select id="jenis_kegiatan" class="{{ $select }} col-span-2">
                    <option>- PILIH -</option>
                    <option>- Pelatihan -</option>
                    <option>- Workshop -</option>
                    <option>- Webinar -</option>
                    <option>- Seminar -</option>
                </select>
            </div>

            <!-- Cakupan -->
            <div class="grid grid-cols-3 gap-4 items-center">
                <label for="cakupan" class="{{ $label }}">Cakupan Kegiatan</label>
                <select id="cakupan" class="{{ $select }} col-span-2">
                    <option>- PILIH -</option>
                    <option>- Teknis -</option>
                    <option>- Manajerial -</option>
                    <option>- Sosiokultural -</option>
                </select>
            </div>

            <!-- Jenis Materi -->
            <div class="grid grid-cols-3 gap-4 items-center">
                <label for="jenis_materi" class="{{ $label }}">Jenis Materi</label>
                <select id="jenis_materi" class="{{ $select }} col-span-2">
                    <option>- PILIH -</option>
                    <option>- Kurikulum -</option>
                    <option>- Non Kurikulum -</option>
                </select>
            </div>

            <!-- Metode -->
            <div class="grid grid-cols-3 gap-4 items-center">
                <label for="metode" class="{{ $label }}">Metode</label>
                <select id="metode" class="{{ $select }} col-span-2">
                    <option>- PILIH -</option>
                    <option>- Blended -</option>
                    <option>- Luring -</option>
                    <option>- Daring -</option>
                </select>
            </div>

            <!-- Angkatan -->
            <div class="grid grid-cols-3 gap-4 items-center">
                <label for="angkatan" class="{{ $label }}">Angkatan</label>
                <select id="angkatan" class="{{ $select }} col-span-2">
                    <option>- PILIH -</option>
                </select>
            </div>

            <!-- Bentuk -->
            <div class="grid grid-cols-3 gap-4 items-center">
                <label for="bentuk" class="{{ $label }}">Bentuk</label>
                <input type="text" id="bentuk" class="{{ $input }} col-span-2">
            </div>

            <!-- Institusi Kerjasama -->
            <div class="grid grid-cols-3 gap-4 items-center">
                <label for="institusi_kerjasama" class="{{ $label }}">Institusi Kerjasama</label>
                <input type="text" id="institusi_kerjasama" class="{{ $input }} col-span-2">
            </div>

            <!-- Target Peserta -->
            <div class="grid grid-cols-3 gap-4 items-center">
                <label for="peserta" class="{{ $label }}">Target Peserta</label>
                <input type="text" id="peserta" class="{{ $input }} col-span-2">
            </div>

            <!-- Waktu Kegiatan -->
            <div class="grid grid-cols-3 gap-4 items-center">
                <label for="tgl_mulai" class="{{ $label }}">Mulai</label>
                <div class="col-span-2 flex gap-4">
                <input type="date" id="tgl_mulai" class="{{ $input }}" placeholder="Mulai">
                <label for="tgl_selesai" class="{{ $label }}">Selesai</label>
                <input type="date" id="tgl_selesai" class="{{ $input }}" placeholder="Selesai">
                </div>
            </div>

            <!-- Anggaran -->
            <div class="grid grid-cols-3 gap-4 items-center">
                <label for="anggaran" class="{{ $label }}">Anggaran</label>
                <input type="number" id="anggaran" class="{{ $input }} col-span-2">
            </div>

            <!-- Unit Pengusul -->
            <div class="grid grid-cols-3 gap-4 items-center">
                <label for="unit_pengusul" class="{{ $label }}">Unit Pengusul</label>
                <input type="text" id="unit_pengusul" class="{{ $input }} col-span-2">
            </div>

            <!-- Nama PIC -->
            <div class="grid grid-cols-3 gap-4 items-center">
                <label for="pic" class="{{ $label }}">Nama PIC</label>
                <input type="text" id="pic" class="{{ $input }} col-span-2">
            </div>

            <!-- WA PIC -->
            <div class="grid grid-cols-3 gap-4 items-center">
                <label for="wa_pic"  class="{{ $label }}">WA PIC</label>
                <input type="text" id="wa_pic" class="{{ $input }} col-span-2">
            </div>

            <!-- Action -->
            <div class="flex justify-center gap-4 pt-6">
                <button type="submit"
                    class="flex items-center gap-2 bg-green-500 text-white px-6 py-2 rounded-full hover:bg-green-600">
                    SIMPAN
                </button>

                <a href="{{ url()->previous() }}"
                   class="flex items-center gap-2 bg-gray-400 text-white px-6 py-2 rounded-full hover:bg-gray-500">
                    BATAL
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
