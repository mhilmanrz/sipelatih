<section>
    <div class="mb-4 flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-800">Dokumen Kegiatan</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600 border border-gray-200 rounded-lg overflow-hidden">
            <thead class="bg-[#007a7a] text-white">
                <tr>
                    <th class="px-6 py-3 font-semibold w-16 text-center">No.</th>
                    <th class="px-6 py-3 font-semibold">Nama Dokumen</th>
                    <th class="px-6 py-3 font-semibold text-center w-40">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-center">1</td>
                    <td class="px-6 py-4 font-medium text-gray-800">Formulir Permintaan Kegiatan</td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('kegiatan.pdf.formulir', $kegiatan->id) }}" target="_blank" class="inline-flex items-center gap-1 bg-[#007a7a] hover:bg-teal-700 text-white px-3 py-1.5 rounded text-xs font-medium transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                            Download PDF
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
