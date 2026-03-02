@extends('layout.LayoutPengusul')

@section('title', 'Penilaian Diklat')

@section('styles')
<link rel="stylesheet" href="{{ assets('css/LayoutPengusul.css') }}">
<link rel="stylesheet" href="{{ assets('css/penilaian.css') }}">
<script src="https://cdn.tailwindcss.com"></script>

<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#11b9c6',
                    sidebar: '#0f172a'
                }
            }
        }
    }
</script>
@endsection

@section('content')

<div class="layout">

    <div class="content">
        <!-- MAIN -->
        <main class="main">

            <!-- CONTENT -->
            <section class="p-6 flex-1 space-y-6">

                <h2 class="text-xl font-semibold tracking-wide">
                    PENGAJUAN DIKLAT
                </h2>

                <!-- INFO BOX -->
                <div class="bg-white rounded shadow p-4 text-sm space-y-2">
                    <div><span class="font-semibold">Nama Kegiatan</span> : <b>Workshop ICTEC</b></div>
                    <div><span class="font-semibold">Pengusul</span> : <b>RSUPN Dr. Cipto Mangunkusumo</b></div>
                    <div><span class="font-semibold">Jenis Kegiatan</span> : <b>Workshop</b></div>
                    <div><span class="font-semibold">Cakupan</span> : <b>Nasional</b></div>
                    <div><span class="font-semibold">Jenis Materi</span> : <b>Spesifik Keprofesian</b></div>
                    <div><span class="font-semibold">Waktu Pelaksanaan</span> : <b>1 Nov 2025 s/d 25 Nov 2025</b></div>
                    <div><span class="font-semibold">Status</span> : <b>Draft</b></div>
                </div>

                <!-- TAB MENU -->
                <div class="bg-white rounded shadow">
                    <div class="flex flex-wrap border-b text-sm">
                        <button onclick="window.location='{{ route('usulan.kegiatan') }}'">Kegiatan</button>
        <button onclick="window.location='{{ route('usulan.sasaran') }}'">Sasaran Profesi</button>
        <button onclick="window.location='{{ route('usulan.kak') }}'">KAK</button>
        <button onclick="window.location='{{ route('usulan.materi') }}'">Materi</button>
        <button onclick="window.location='{{ route('usulan.narasumber') }}'">Narasumber</button>
        <button onclick="window.location='{{ route('usulan.peserta') }}'">Peserta</button>
        <button onclick="window.location='{{ route('usulan.pengiriman') }}'" >Pengiriman</button>
        <button onclick="window.location='{{ route('usulan.penilaian') }}'" class="active">Penilaian</button>
        <button onclick="window.location='{{ route('usulan.sertifikat') }}'">Sertifikat</button>
                    </div>

                    <!-- TABLE -->
                    <div class="p-4 overflow-x-auto">
                        <table class="w-full text-sm border">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-3 py-2 text-center">NO.</th>
                                    <th class="border px-3 py-2">Tanggal</th>
                                    <th class="border px-3 py-2">Status</th>
                                    <th class="border px-3 py-2">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border px-3 py-2 text-center">1</td>
                                    <td class="border px-3 py-2">29 - 12 - 2025</td>
                                    <td class="border px-3 py-2">
                                        <span class="bg-red-500 text-white px-2 py-1 rounded text-xs">
                                            ✖ Ditolak
                                        </span>
                                    </td>
                                    <td class="border px-3 py-2">-</td>
                                </tr>
                                <tr>
                                    <td class="border px-3 py-2 text-center">2</td>
                                    <td class="border px-3 py-2">01 - 01 - 2026</td>
                                    <td class="border px-3 py-2">
                                        <span class="bg-yellow-500 text-white px-2 py-1 rounded text-xs">
                                            🔧 Butuh Perbaikan
                                        </span>
                                    </td>
                                    <td class="border px-3 py-2 text-red-600 font-medium">
                                        Lengkapi RAB
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border px-3 py-2 text-center">3</td>
                                    <td class="border px-3 py-2">05 - 01 - 2026</td>
                                    <td class="border px-3 py-2">
                                        <span class="bg-green-600 text-white px-2 py-1 rounded text-xs">
                                            ✔ Disetujui
                                        </span>
                                    </td>
                                    <td class="border px-3 py-2">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

            </section>

        </main>
    </div>

</div>

@endsection

@section('scripts')
<script src="{{ assets('js/LayoutPengusul.js') }}"></script>
<script src="{{ assets('js/penilaian.js') }}"></script>
@endsection