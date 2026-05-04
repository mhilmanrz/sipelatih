<x-layouts.app>
    <x-slot:title>Evaluasi III</x-slot>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/LayoutSuperAdmin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/evaluasi3.css') }}">
    @endpush

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
                <thead class="bg-[#007a7a] text-white">
                    <tr>
                        <th class="border border-gray-400 p-4">NO.</th>
                        <th class="border border-gray-400 p-4">Nama Pegawai</th>
                        <th class="border border-gray-400 p-4">Jabatan</th>
                        <th class="border border-gray-400 p-4">NIP/NIPS</th>
                        <th class="border border-gray-400 p-4">Unit Kerja</th>
                    </tr>
                </thead>

                <tbody>
                    <tr class="text-center">
                        <td class="border border-gray-400 p-4">1</td>
                        <td class="border border-gray-400 p-4 text-[#007a7a] font-semibold">
                            Nina Persik
                        </td>
                        <td class="border border-gray-400 p-4">Administrasi</td>
                        <td class="border border-gray-400 p-4">11116482655488234</td>
                        <td class="border border-gray-400 p-4">TK Diklat</td>
                    </tr>

                    <tr class="text-center">
                        <td class="border border-gray-400 p-4">2</td>
                        <td class="border border-gray-400 p-4 text-[#007a7a] font-semibold">
                            Saskya Gotik
                        </td>
                        <td class="border border-gray-400 p-4">Administrasi</td>
                        <td class="border border-gray-400 p-4">11116482655462584</td>
                        <td class="border border-gray-400 p-4">TK Diklat</td>
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

    @push('scripts')
    <script src="{{ asset('js/LayoutSuperAdmin.js') }}"></script>
    <script src="{{ asset('js/evaluasi3.js') }}"></script>
    @endpush
</x-layouts.app>
