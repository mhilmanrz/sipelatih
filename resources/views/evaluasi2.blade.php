@extends('layout.LayoutSuperAdmin')

@section('title', 'Nilai Pre Test & Post Test')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/LayoutSuperAdmin.css') }}">
<link rel="stylesheet" href="{{ asset('css/evaluasi2.css') }}">
@endpush
@section('content')

<div class="bg-primary min-h-screen font-sans">

    <!-- HEADER -->
    <div class="bg-primary px-8 py-6">
        <h2 class="text-white text-3xl font-bold">
            NILAI PRE TEST & POST TEST
        </h2>
    </div>

    <!-- CONTENT -->
    <div class="px-8 py-6">

        <!-- CARD -->
        <div class="bg-white rounded-2xl p-6 mb-6 shadow">
            <h3 class="text-header font-bold text-lg mb-4">
                NILAI PRE TEST DAN POST TEST
            </h3>

            <!-- DOWNLOAD BUTTON -->
            <a
                href="{{ asset('laporan/laporan-pre-post-test.pdf') }}"
                download
                class="inline-flex items-center gap-2 bg-button text-white px-5 py-2 rounded-full shadow hover:opacity-90 transition"
            >
                ⬇ Download Laporan
            </a>
        </div>

        <!-- TABLE -->
        <div class="bg-white rounded-xl overflow-hidden shadow">
            <table class="w-full border-collapse">
                <thead class="bg-header text-white text-sm">
                    <tr>
                        <th class="p-3 border">No.</th>
                        <th class="p-3 border">Nama Peserta</th>
                        <th class="p-3 border">NIP/NPS</th>
                        <th class="p-3 border">Unit Kerja</th>
                        <th class="p-3 border">Pre Test</th>
                        <th class="p-3 border">Post Test</th>
                        <th class="p-3 border">Nilai Praktik</th>
                    </tr>
                </thead>
                <tbody class="text-center text-sm">

                    <tr class="border-t">
                        <td class="p-3 border">1</td>
                        <td class="p-3 border">Andi Ade Wijaya</td>
                        <td class="p-3 border">nps1233521</td>
                        <td class="p-3 border">TK Diklat</td>
                        <td class="p-3 border">80</td>
                        <td class="p-3 border">90</td>
                        <td class="p-3 border">-</td>
                    </tr>

                    <tr class="border-t">
                        <td class="p-3 border">2</td>
                        <td class="p-3 border">Rangga Moela</td>
                        <td class="p-3 border">23153769357368</td>
                        <td class="p-3 border">ICTEC</td>
                        <td class="p-3 border">55</td>
                        <td class="p-3 border">75</td>
                        <td class="p-3 border">-</td>
                    </tr>

                </tbody>
            </table>
        </div>

    </div>
</div>

@endsection
@push('scripts')
<script src="{{ asset('js/LayoutSuperAdmin.js') }}"></script>
<script src="{{ asset('js/evaluasi2.js') }}"></script>
@endpush