@extends('layout.LayoutPenyelenggaraan')

@section('title', 'Input Nilai')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/LayoutPenyelenggaraan.css') }}">
<link rel="stylesheet" href="{{ asset('css/InputNilai.css') }}">
@endpush

@section('content')

<div class="content input-nilai-page bg-[#14B8C5] min-h-screen p-6">

    <h1 class="text-white text-3xl font-bold mb-8">
        INPUT NILAI PRE TEST & POST TEST
    </h1>

    <!-- ACTION BUTTON -->
    <div class="flex flex-wrap gap-4 mb-8">

        <button id="openModal"
            class="bg-[#006D73] text-yellow-300 px-6 py-3 rounded-full flex items-center gap-2 shadow hover:opacity-90">
            <i class="fa-solid fa-plus"></i>
            Tambah
        </button>

        <button
            class="bg-[#1C9AA3] text-white px-6 py-3 rounded-full flex items-center gap-2 shadow hover:opacity-90">
            <i class="fa-solid fa-pen"></i>
            Edit
        </button>

    </div>

    <!-- TABLE -->
    <div class="bg-[#007C82] rounded-[20px] shadow overflow-x-auto">

        <table class="w-full min-w-[1000px] text-sm">
            <thead class="bg-[#007C82] text-white">
                <tr>
                    <th class="py-4 px-3 text-center">NO.</th>
                    <th class="py-4 px-3">Nama Peserta</th>
                    <th class="py-4 px-3 text-center">NIP/NPS</th>
                    <th class="py-4 px-3 text-center">Unit Kerja</th>
                    <th class="py-4 px-3 text-center">Pre Test</th>
                    <th class="py-4 px-3 text-center">Post Test</th>
                    <th class="py-4 px-3 text-center">Nilai Praktik</th>
                    <th class="py-4 px-3 text-center">Akumulasi</th>
                    <th class="py-4 px-3 text-center">Batas Lulus</th>
                    <th class="py-4 px-3 text-center">Status</th>
                    <th class="py-4 px-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="bg-white text-gray-800">

                <tr class="border-b">
                    <td class="py-4 text-center">1</td>
                    <td class="py-4">Andi Ade Wijaya</td>
                    <td class="py-4 text-center">nps1233521</td>
                    <td class="py-4 text-center">TK Diklat</td>
                    <td class="py-4 text-center">80</td>
                    <td class="py-4 text-center">90</td>
                    <td class="py-4 text-center">86</td>
                    <td class="py-4 text-center">88</td>
                    <td class="py-4 text-center">80</td>
                    <td class="py-4 text-center">
                        <span class="bg-green-600 text-white px-3 py-1 rounded-full text-xs">
                            Lulus
                        </span>
                    </td>
                    <td class="py-4 text-center">
                        <button class="bg-[#1C9AA3] text-white px-4 py-1 rounded-full text-xs shadow">
                            <i class="fa-solid fa-pen mr-1"></i> Edit
                        </button>
                    </td>
                </tr>

            </tbody>
        </table>

    </div>

    <!-- FOOTER -->
    <div class="flex flex-wrap gap-3 justify-between items-center mt-6 text-white text-sm">
        <span>Showing 1 to 1 of 1 entries</span>

        <div class="flex items-center gap-2">
            <button class="bg-white text-[#007C82] px-4 py-2 rounded-lg shadow">Previous</button>
            <span class="bg-[#006D73] px-4 py-2 rounded-lg">1</span>
            <button class="bg-white text-[#007C82] px-4 py-2 rounded-lg shadow">Next</button>
        </div>
    </div>

</div>

@endsection


@push('scripts')
<script src="{{ asset('js/LayoutPenyelenggaraan.js') }}"></script>
<script src="{{ asset('js/InputNilai.js') }}"></script>
@endpush