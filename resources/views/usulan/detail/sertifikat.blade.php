@extends('layout.LayoutPengusul')

@section('title','E-Sertifikat')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/sertifikat.css') }}">
@endpush

@section('content')

<main class="content">
    <div class="card">

        <!-- TAB MENU -->
        <div class="tabs">
              <button onclick="window.location='{{ route('usulan.kegiatan') }}'" class="tab">Kegiatan</button>
        <button onclick="window.location='{{ route('usulan.sasaran') }}'" class="tab">Sasaran Profesi</button>
        <button onclick="window.location='{{ route('usulan.kak') }}'" class="tab">KAK</button>
        <button onclick="window.location='{{ route('usulan.materi') }}'"class="tab">Materi</button>
        <button onclick="window.location='{{ route('usulan.narasumber') }}'" class="tab">Narasumber</button>
        <button onclick="window.location='{{ route('usulan.peserta') }}'" class="tab">Peserta</button>
        <button onclick="window.location='{{ route('usulan.pengiriman') }}'" class="tab">Pengiriman</button>
        <button onclick="window.location='{{ route('usulan.penilaian') }}'" class="tab">Penilaian</button>
        <button onclick="window.location='{{ route('usulan.sertifikat') }}'" class="tab active">Sertifikat</button>
        </div>

        <!-- CONTENT -->
        <div class="card-body">
            <p class="text">
                Untuk mengunduh e-sertifikat, klik tombol dibawah sini.
            </p>

            <button class="btn-sertifikat" onclick="downloadSertifikat()">
                E-SERTIFIKAT
            </button>
        </div>

    </div>
</main>

@endsection

@push('scripts')
<script src="{{ asset('js/sertifikat.js') }}"></script>
<script src="{{ asset('js/LayoutPengusul.js') }}"></script>
@endpush