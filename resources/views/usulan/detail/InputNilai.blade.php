@extends('layout.LayoutPenyelenggaraan') {{-- Sesuaikan dengan nama file layout Anda --}}

@section('title', 'Input Nilai')

@push('styles')
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="{{ asset('css/InputNilai.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    /* Tailwind Config Integration */
    .bg-contentBg { background-color: #1fb0b8; }
    .bg-tableHeader { background-color: #0d5c57; }
    .bg-editBtn { background-color: #1c9aa3; }
    .bg-lulus { background-color: #16a34a; }
    .rounded-pill { border-radius: 999px; }

    /* Custom UI Components */
    .page-title-text { font-size: 24px; font-weight: 800; color: white; margin-bottom: 20px; }
    .info-card { background: white; border-radius: 15px; padding: 20px; color: #333; margin-bottom: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    .info-grid { display: grid; grid-template-columns: 180px auto; gap: 8px 15px; font-size: 14px; }
    .info-grid div { font-weight: bold; color: #1f5f59; }
</style>
@endpush

@section('content')
<main class="flex-1 p-10 bg-contentBg min-h-screen">
    
    <div class="page-title-text">PENGAJUAN DIKLAT</div>

    <div class="info-card">
        <div class="info-grid">
            <div>Nama Kegiatan</div><span>: Workshop ICTEC</span>
            <div>Pengusul</div><span>: RSUPN Dr. Cipto Mangunkusumo</span>
            <div>Jenis Kegiatan</div><span>: Workshop</span>
            <div>Cakupan</div><span>: Nasional</span>
            <div>Waktu Pelaksanaan</div><span>: 1 Nov 2025 s/d 25 Nov 2025</span>
            <div>Status</div><span>: Draft</span>
        </div>
    </div>

    <div class="tabs">
         <button onclick="window.location='{{ route('usulan.kegiatan') }}'" class="tab">Kegiatan</button>
        <button onclick="window.location='{{ route('usulan.sasaran') }}'" class="tab">Sasaran Profesi</button>
        <button onclick="window.location='{{ route('usulan.kak') }}'" class="tab">KAK</button>
        <button onclick="window.location='{{ route('usulan.materi') }}'"class="tab">Materi</button>
        <button onclick="window.location='{{ route('usulan.narasumber') }}'" class="tab">Narasumber</button>
        <button onclick="window.location='{{ route('usulan.peserta') }}'" class="tab">Peserta</button>
        <button onclick="window.location='{{ route('usulan.pengiriman') }}'" class="tab">Pengiriman</button>
        <button onclick="window.location='{{ route('usulan.penilaian') }}'" class="tab Active">Input Nilai</button>
        <button onclick="window.location='{{ route('usulan.penilaian') }}'" class="tab">Penilaian</button>
        <button onclick="window.location='{{ route('usulan.sertifikat') }}'" class="tab">Sertifikat</button>
        </div>
    </div>

    <h1 class="text-4xl font-extrabold tracking-wide mb-8 text-white uppercase">
        Input Nilai Pre Test & Post Test
    </h1>

    <div class="flex flex-wrap gap-4 mb-8">
        <button id="openModal" class="bg-teal-800 px-8 py-3 rounded-pill shadow-lg flex items-center gap-3 hover:scale-105 transition">
            <i class="fa-solid fa-plus text-yellow-300 text-xl"></i>
            <span class="text-yellow-300 font-bold text-lg">Tambah</span>
        </button>

        <button class="bg-editBtn px-8 py-3 rounded-pill shadow-lg flex items-center gap-3">
            <i class="fa-solid fa-pen text-white text-xl"></i>
            <span class="text-white font-bold text-lg">Edit</span>
        </button>
    </div>

    <div class="bg-white rounded-xl overflow-hidden shadow-lg">
        <table class="w-full text-sm text-center">
            <thead class="bg-tableHeader text-white">
                <tr>
                    <th class="py-4">NO.</th>
                    <th>Nama Peserta</th>
                    <th>NIP/NPS</th>
                    <th>Unit Kerja</th>
                    <th>Pre Test</th>
                    <th>Post Test</th>
                    <th>Nilai Praktik</th>
                    <th>Akumulasi</th>
                    <th>Nilai Batas Lulus</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody" class="text-gray-700">
                {{-- Data bisa di-looping di sini menggunakan @foreach($peserta as $p) --}}
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="py-4">1</td>
                    <td class="text-left px-4">Andi Ade Wijaya</td>
                    <td>nps1233521</td>
                    <td>TK Diklat</td>
                    <td>80</td>
                    <td>90</td>
                    <td>86</td>
                    <td>88</td>
                    <td>80</td>
                    <td>
                        <span class="bg-lulus text-white px-4 py-1 rounded-pill text-xs font-semibold">Lulus</span>
                    </td>
                    <td>
                        <button class="bg-editBtn text-white text-xs px-4 py-1 rounded-pill editBtn hover:opacity-80">
                            <i class="fa-solid fa-pen mr-1"></i>EDIT
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</main>

{{-- MODAL --}}
<div id="modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
    <div class="bg-gray-100 w-[650px] max-w-[95%] rounded-3xl p-10 relative">
        <button id="closeModal" class="absolute top-5 right-6 text-2xl text-gray-400 hover:text-red-500">
            <i class="fa-solid fa-circle-xmark"></i>
        </button>

        <form id="nilaiForm" class="space-y-5 text-gray-700">
            @csrf {{-- Penting di Laravel untuk keamanan --}}
            <div class="grid grid-cols-3 items-center gap-4">
                <label class="font-semibold text-teal-700">Nama Peserta</label>
                <input id="nama" class="col-span-2 border-2 border-teal-600 rounded-full px-5 py-2 focus:outline-none focus:ring-2 ring-teal-300">
            </div>
            {{-- Tambahkan field lainnya sesuai kebutuhan --}}
            
            <div class="flex justify-center gap-6 pt-6">
                <button type="submit" class="bg-teal-700 text-yellow-300 px-10 py-3 rounded-full font-bold shadow-md hover:bg-teal-800 transition">SIMPAN</button>
                <button type="button" id="cancelModal" class="bg-gray-400 text-white px-10 py-3 rounded-full font-bold shadow-md hover:bg-gray-500 transition">BATAL</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/InputNilai.js') }}"></script>
<script src="{{ asset('js/LayoutPenyelenggaraan.js') }}"></script>
@endpush