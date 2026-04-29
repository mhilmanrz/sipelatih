@extends('layout.LayoutSuperAdmin')

@section('title', 'Evaluasi III')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/LayoutSuperAdmin.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/evaluasi3.css') }}">
@endpush

@section('content')

<div class="bg-[#13b9c6] min-h-screen font-sans">

    <!-- TITLE -->
    <section class="px-8 py-6">
        <x-page-title>EVALUASI III</x-page-title>
    </section>

    <!-- CARD DOWNLOAD -->
    <section class="bg-white mx-8 p-6 rounded-[25px] shadow">
        <h2 class="text-[#007a7a] text-xl font-bold mb-4">MONITORING</h2>

        <a
            href="{{ asset('laporan/laporan-evaluasi-iii.pdf') }}"
            download
            class="inline-flex items-center gap-2 bg-[#007a7a] text-white px-5 py-2 rounded-full font-semibold hover:bg-[#006666]"
        >
            ⬇ Download Laporan
        </a>
    </section>

    <!-- TABLE -->
    <section class="mx-8 mt-6 bg-white rounded-[20px] overflow-hidden shadow">
        <table class="w-full border-collapse">
            <thead class="bg-[#007a7a] border border-white py-3 px-4 font-semibold">
                <tr>
                    <th class="-400 border border-white py-3 px-4 font-semibold">NO.</th>
                    <th class="-400 border border-white py-3 px-4 font-semibold">Nama Pegawai</th>
                    <th class="-400 border border-white py-3 px-4 font-semibold">Jabatan</th>
                    <th class="-400 border border-white py-3 px-4 font-semibold">NIP/NIPS</th>
                    <th class="-400 border border-white py-3 px-4 font-semibold">Unit Kerja</th>
                </tr>
            </thead>

            <tbody>
                <tr class="text-center">
                    <td class="-400 border border-gray-200 py-3 px-4">1</td>
                    <td class="-400 text-[#007a7a] font-semibold border border-gray-200 py-3 px-4">
                        Nina Persik
                    </td>
                    <td class="-400 border border-gray-200 py-3 px-4">Administrasi</td>
                    <td class="-400 border border-gray-200 py-3 px-4">11116482655488234</td>
                    <td class="-400 border border-gray-200 py-3 px-4">TK Diklat</td>
                </tr>

                <tr class="text-center">
                    <td class="-400 border border-gray-200 py-3 px-4">2</td>
                    <td class="-400 text-[#007a7a] font-semibold border border-gray-200 py-3 px-4">
                        Saskya Gotik
                    </td>
                    <td class="-400 border border-gray-200 py-3 px-4">Administrasi</td>
                    <td class="-400 border border-gray-200 py-3 px-4">11116482655462584</td>
                    <td class="-400 border border-gray-200 py-3 px-4">TK Diklat</td>
                </tr>
            </tbody>
        </table>

        <!-- FOOTER -->
        <div class="flex flex-wrap gap-3 justify-between items-center mt-6 px-6 pb-6 text-sm">
            <span>Showing 1 to 2 of 2 entries</span>

            <div class="flex items-center gap-2">
                <button class="bg-white text-[#007C82] px-4 py-2 rounded-lg shadow">Previous</button>
                <span class="bg-[#006D73] text-white px-4 py-2 rounded-lg">1</span>
                <button class="bg-white text-[#007C82] px-4 py-2 rounded-lg shadow">Next</button>
            </div>
        </div>

    </section>

</div>

@endsection

@push('scripts')
<script src="{{ asset('js/LayoutSuperAdmin.js') }}"></script>
<script src="{{ asset('js/evaluasi3.js') }}"></script>
@endpush