<section>
    <div class="overflow-x-auto">
        <table class="w-full text-sm border-collapse border border-gray-200">
            <thead class="bg-[#007a7a] text-white">
                <tr>
                    <th class="text-center w-16 border border-white py-3 px-4 font-semibold text-sm">No.</th>
                    <th class="text-left border border-white py-3 px-4 font-semibold text-sm">Nama Dokumen</th>
                    <th class="text-center w-40 border border-white py-3 px-4 font-semibold text-sm">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="text-center border border-gray-200 py-3 px-4">1</td>
                    <td class="border border-gray-200 py-3 px-4 text-gray-900">Formulir Permintaan Kegiatan</td>
                    <td class="text-center border border-gray-200 py-3 px-4">
                        <a href="{{ route('kegiatan.pdf.formulir', $kegiatan->id) }}" target="_blank" class="inline-flex items-center gap-1 bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                            Download PDF
                        </a>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="text-center border border-gray-200 py-3 px-4">2</td>
                    <td class="border border-gray-200 py-3 px-4 text-gray-900">Nota Dinas Permohonan Narasumber</td>
                    <td class="text-center border border-gray-200 py-3 px-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('kegiatan.nota-dinas.pdf', $kegiatan->id) }}" target="_blank" class="inline-flex items-center gap-1 bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                                PDF
                            </a>
                            <a href="{{ route('kegiatan.nota-dinas.docx', $kegiatan->id) }}" class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                                DOCX
                            </a>
                        </div>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="text-center border border-gray-200 py-3 px-4">3</td>
                    <td class="border border-gray-200 py-3 px-4 text-gray-900">Surat Pemanggilan Peserta</td>
                    <td class="text-center border border-gray-200 py-3 px-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('kegiatan.surat-pemanggilan.pdf', $kegiatan->id) }}" target="_blank" class="inline-flex items-center gap-1 bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                                PDF
                            </a>
                            <a href="{{ route('kegiatan.surat-pemanggilan.docx', $kegiatan->id) }}" class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                                DOCX
                            </a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>