@extends('layout.LayoutSuperAdmin')

@section('title', 'Nilai Pre Test & Post Test')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/LayoutSuperAdmin.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/evaluasi2.css') }}">
@endpush
@section('content')

<div class="bg-primary min-h-screen font-sans">

    <!-- HEADER -->
    <div class="bg-primary px-8 py-6">
        <x-page-title>NILAI PRE TEST & POST TEST</x-page-title>
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
                <thead class="bg-[#007a7a] border border-white py-3 px-4 font-semibold">
                    <tr>
                        <th class="border border-white py-3 px-4 font-semibold">No.</th>
                        <th class="border border-white py-3 px-4 font-semibold">Nama Peserta</th>
                        <th class="border border-white py-3 px-4 font-semibold">NIP/NPS</th>
                        <th class="border border-white py-3 px-4 font-semibold">Unit Kerja</th>
                        <th class="border border-white py-3 px-4 font-semibold">Pre Test</th>
                        <th class="border border-white py-3 px-4 font-semibold">Post Test</th>
                        <th class="border border-white py-3 px-4 font-semibold">Nilai Praktik</th>
                    </tr>
                </thead>
                <tbody class="text-center text-sm">

                    <tr class="border-t">
                        <td class="border border-gray-200 py-3 px-4">1</td>
                        <td class="border border-gray-200 py-3 px-4">Andi Ade Wijaya</td>
                        <td class="border border-gray-200 py-3 px-4">nps1233521</td>
                        <td class="border border-gray-200 py-3 px-4">TK Diklat</td>
                        <td class="border border-gray-200 py-3 px-4">80</td>
                        <td class="border border-gray-200 py-3 px-4">90</td>
                        <td class="border border-gray-200 py-3 px-4">-</td>
                    </tr>

                    <tr class="border-t">
                        <td class="border border-gray-200 py-3 px-4">2</td>
                        <td class="border border-gray-200 py-3 px-4">Rangga Moela</td>
                        <td class="border border-gray-200 py-3 px-4">23153769357368</td>
                        <td class="border border-gray-200 py-3 px-4">ICTEC</td>
                        <td class="border border-gray-200 py-3 px-4">55</td>
                        <td class="border border-gray-200 py-3 px-4">75</td>
                        <td class="border border-gray-200 py-3 px-4">-</td>
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