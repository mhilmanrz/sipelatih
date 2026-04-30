<section style="margin-top: 2rem;">
    <!-- CARD -->
    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 2rem;">

        <div style="font-weight: bold; font-size: 1.125rem; margin-bottom: 1.5rem; color: #111827;">
            Riwayat Penilaian & Status Dokumen
        </div>

        @php
            $statuses = $kegiatan->statuses()->orderBy('created_at', 'desc')->get();
        @endphp

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse border border-gray-200">
                <thead class="bg-[#007a7a] border border-white py-3 px-4 font-semibold">
                    <tr>
                        <th class="-300 text-center w-16 border border-white py-3 px-4 font-semibold">NO.</th>
                        <th class="-300 text-left w-48 border border-white py-3 px-4 font-semibold">Tanggal Waktu</th>
                        <th class="-300 text-left w-48 border border-white py-3 px-4 font-semibold">Status Mutasi</th>
                        <th class="-300 text-left border border-white py-3 px-4 font-semibold">Keterangan / Catatan Reviewer</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($statuses as $index => $hist)
                        <tr class="hover:bg-gray-50">
                            <td class="-300 text-center text-gray-600 border border-gray-200 py-3 px-4">
                                {{ $statuses->count() - $index }} 
                            </td>
                            <td class="-300 text-gray-600 font-mono text-sm border border-gray-200 py-3 px-4">
                                {{ $hist->created_at->format('d M Y - H:i') }}
                            </td>
                            <td class="-300 border border-gray-200 py-3 px-4">
                                @if($hist->status === 'draft')
                                    <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded text-xs font-bold uppercase track-wide">
                                        ✎ Draft
                                    </span>
                                @elseif($hist->status === 'submitted')
                                    <span class="bg-blue-500 text-white px-3 py-1 rounded text-xs font-bold uppercase shadow-sm track-wide">
                                        🚀 Terkirim / Diajukan
                                    </span>
                                @elseif($hist->status === 'revision')
                                    <span class="bg-yellow-500 text-white px-3 py-1 rounded text-xs font-bold uppercase shadow-sm track-wide">
                                        🔧 Butuh Perbaikan
                                    </span>
                                @elseif($hist->status === 'accepted')
                                    <span class="bg-green-500 text-white px-3 py-1 rounded text-xs font-bold uppercase shadow-sm track-wide">
                                        ✔ Disetujui
                                    </span>
                                @else
                                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded text-xs font-bold uppercase">
                                        {{ $hist->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="-300 {{ $hist->status === 'revision' ? 'text-red-600 font-medium' : 'text-gray-700' }} border border-gray-200 py-3 px-4">
                                {{ $hist->note ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="-300 text-center text-gray-400 border border-gray-200 py-3 px-4">
                                Belum ada riwayat tercatat untuk usulan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</section>
