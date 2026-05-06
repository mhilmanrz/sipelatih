<x-layouts.app>
    @section('title', 'Evaluasi I')

    <x-page-title>EVALUASI I</x-page-title>

    {{-- CARD --}}
    <div class="bg-white rounded-xl p-5 shadow-sm mb-6">
        <h3 class="mb-4 border-b border-gray-100 pb-3 text-lg font-semibold text-gray-800">
            EVALUASI PENYELENGGARAAN
        </h3>

        <a href="{{ asset('laporan/laporan-evaluasi.pdf') }}"
            class="bg-[#006D73] text-white px-6 py-3 rounded-full flex items-center gap-2 shadow hover:opacity-90 w-fit">
            ⬇ Download Laporan
        </a>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <x-table>
            <x-slot name="header">
                <tr>
                    <th class="px-4 py-3 text-center w-16">NO.</th>
                    <th class="px-4 py-3 text-left">Nama Kegiatan</th>
                    <th class="px-4 py-3 text-center">Tanggal</th>
                    <th class="px-4 py-3 text-center">Tanggal Upload</th>
                    <th class="px-4 py-3 text-center">Laporan</th>
                </tr>
            </x-slot>

            <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                <td class="text-center border border-gray-200 py-3 px-4 text-sm text-gray-500">1</td>
                <td class="border border-gray-200 py-3 px-4 text-sm text-gray-900">Workshop ICTEC</td>
                <td class="text-center border border-gray-200 py-3 px-4 text-sm text-gray-900">15 Januari 2026</td>
                <td class="text-center border border-gray-200 py-3 px-4 text-sm text-gray-900">16 Januari 2026</td>
                <td class="text-center border border-gray-200 py-3 px-4 text-sm">
                    <a href="{{ asset('laporan/workshop-ictec-15-jan-2026.pdf') }}" target="_blank"
                        class="text-white px-3 py-1.5 rounded text-sm font-semibold transition hover:opacity-85"
                        style="background-color: #3b82f6;">Lihat</a>
                </td>
            </tr>

            <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                <td class="text-center border border-gray-200 py-3 px-4 text-sm text-gray-500">2</td>
                <td class="border border-gray-200 py-3 px-4 text-sm text-gray-900">Workshop ICTEC</td>
                <td class="text-center border border-gray-200 py-3 px-4 text-sm text-gray-900">21 Januari 2026</td>
                <td class="text-center border border-gray-200 py-3 px-4 text-sm text-gray-900">23 Januari 2026</td>
                <td class="text-center border border-gray-200 py-3 px-4 text-sm">
                    <a href="{{ asset('laporan/workshop-ictec-21-jan-2026.pdf') }}" target="_blank"
                        class="text-white px-3 py-1.5 rounded text-sm font-semibold transition hover:opacity-85"
                        style="background-color: #3b82f6;">Lihat</a>
                </td>
            </tr>
        </x-table>

        {{-- FOOTER --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-6 py-4 bg-white border-t border-gray-200 rounded-b-[20px]">
            <div class="text-sm text-gray-500">
                Showing <span class="font-semibold text-gray-700">1</span> –
                <span class="font-semibold text-gray-700">2</span> of
                <span class="font-semibold text-gray-700">2</span> entries
            </div>
            <nav class="flex items-center gap-1">
                <span class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-300 bg-gray-100 rounded-lg cursor-not-allowed">Prev</span>
                <span class="inline-flex items-center justify-center w-9 h-9 text-sm font-bold text-white bg-[#205252] rounded-lg shadow-sm shadow-[#205252]/30">1</span>
                <span class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-300 bg-gray-100 rounded-lg cursor-not-allowed">Next</span>
            </nav>
        </div>
    </div>
</x-layouts.app>
