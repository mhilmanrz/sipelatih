@extends('layout.LayoutSuperAdmin')

@section('title', 'Evaluasi I')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/LayoutSuperAdmin.css') }}">
<link rel="stylesheet" href="{{ asset('css/evaluasi1.css') }}">
@endpush
@section('content')

<div class="content evaluasi-page bg-[#14B8C5] min-h-screen p-6">

    <h1 class="text-white text-3xl font-bold mb-8">EVALUASI I</h1>

    <!-- CARD -->
    <div class="bg-white rounded-[24px] p-8 mb-10 shadow w-full">
        <h2 class="text-[#006D73] text-xl font-bold mb-4">
            EVALUASI PENYELENGGARAAN
        </h2>

        <a href="{{ asset('laporan/laporan-evaluasi.pdf') }}"
           class="bg-[#006D73] text-white px-6 py-3 rounded-full flex items-center gap-2 shadow hover:opacity-90 max-sm:w-full justify-center w-fit">
            ⬇ Download Laporan
        </a>
    </div>

    <!-- TABLE -->
    <div class="bg-[#007C82] rounded-[20px] shadow overflow-x-auto">

        <table class="w-full min-w-[700px] text-sm">
            <thead class="bg-[#007C82] text-white">
                <tr>
                    <th class="py-4 px-3 text-center">NO.</th>
                    <th class="py-4 px-3">Nama Kegiatan</th>
                    <th class="py-4 px-3 text-center">Tanggal</th>
                    <th class="py-4 px-3 text-center">Tanggal Upload</th>
                    <th class="py-4 px-3 text-center">Laporan</th>
                </tr>
            </thead>

            <tbody class="bg-white text-gray-800">

                <tr class="border-b">
                    <td class="py-4 text-center">1</td>
                    <td class="py-4">Workshop ICTEC</td>
                    <td class="py-4 text-center">15 Januari 2026</td>
                    <td class="py-4 text-center">16 Januari 2026</td>
                    <td class="py-4 text-center">
                        <a href="{{ asset('laporan/workshop-ictec-15-jan-2026.pdf') }}"
                           target="_blank"
                           class="text-blue-600 underline hover:text-blue-800">
                            (direct link disini)
                        </a>
                    </td>
                </tr>

                <tr>
                    <td class="py-4 text-center">2</td>
                    <td class="py-4">Workshop ICTEC</td>
                    <td class="py-4 text-center">21 Januari 2026</td>
                    <td class="py-4 text-center">23 Januari 2026</td>
                    <td class="py-4 text-center">
                        <a href="{{ asset('laporan/workshop-ictec-21-jan-2026.pdf') }}"
                           target="_blank"
                           class="text-blue-600 underline hover:text-blue-800">
                            (direct link disini)
                        </a>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    <!-- FOOTER -->
    <div class="flex flex-wrap gap-3 justify-between items-center mt-6 text-white text-sm">
        <span>Showing 1 to 2 of 2 entries</span>

        <div class="flex items-center gap-2">
            <button class="bg-white text-[#007C82] px-4 py-2 rounded-lg shadow">Previous</button>
            <span class="bg-[#006D73] px-4 py-2 rounded-lg">1</span>
            <button class="bg-white text-[#007C82] px-4 py-2 rounded-lg shadow">Next</button>
        </div>
    </div>

</div>

@endsection
@push('scripts')
<script src="{{ asset('js/LayoutSuperAdmin.js') }}"></script>
<script src="{{ asset('js/evaluasi2.js') }}"></script>
@endpush