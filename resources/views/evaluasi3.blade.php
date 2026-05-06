<x-layouts.app>
    @section('title', 'Evaluasi III')

    <x-page-title>EVALUASI III</x-page-title>

    {{-- CARD DOWNLOAD --}}
    <div class="bg-white rounded-xl p-5 shadow-sm mb-6">
        <h3 class="mb-4 border-b border-gray-100 pb-3 text-lg font-semibold text-gray-800">
            MONITORING
        </h3>

        <a href="{{ asset('laporan/laporan-evaluasi-iii.pdf') }}" download
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
                    <th class="px-4 py-3 text-left">Nama Pegawai</th>
                    <th class="px-4 py-3 text-left">Jabatan</th>
                    <th class="px-4 py-3 text-left">NIP/NIPS</th>
                    <th class="px-4 py-3 text-left">Unit Kerja</th>
                </tr>
            </x-slot>

            <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                <td class="text-center border border-gray-200 py-3 px-4 text-sm text-gray-500">1</td>
                <td class="border border-gray-200 py-3 px-4 text-sm text-[#007a7a] font-semibold">Nina Persik</td>
                <td class="border border-gray-200 py-3 px-4 text-sm text-gray-900">Administrasi</td>
                <td class="border border-gray-200 py-3 px-4 text-sm text-gray-900">11116482655488234</td>
                <td class="border border-gray-200 py-3 px-4 text-sm text-gray-900">TK Diklat</td>
            </tr>

            <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                <td class="text-center border border-gray-200 py-3 px-4 text-sm text-gray-500">2</td>
                <td class="border border-gray-200 py-3 px-4 text-sm text-[#007a7a] font-semibold">Saskya Gotik</td>
                <td class="border border-gray-200 py-3 px-4 text-sm text-gray-900">Administrasi</td>
                <td class="border border-gray-200 py-3 px-4 text-sm text-gray-900">11116482655462584</td>
                <td class="border border-gray-200 py-3 px-4 text-sm text-gray-900">TK Diklat</td>
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
