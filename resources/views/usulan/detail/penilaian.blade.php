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
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-gray-300 px-4 py-3 text-center text-gray-700 w-16 uppercase text-xs">NO.</th>
                        <th class="border border-gray-300 px-4 py-3 text-left text-gray-700 uppercase text-xs w-48">Tanggal Waktu</th>
                        <th class="border border-gray-300 px-4 py-3 text-left text-gray-700 uppercase text-xs w-48">Status Mutasi</th>
                        <th class="border border-gray-300 px-4 py-3 text-left text-gray-700 uppercase text-xs">Keterangan / Catatan Reviewer</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($statuses as $index => $hist)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-3 text-center text-gray-600">
                                {{ $statuses->count() - $index }} 
                            </td>
                            <td class="border border-gray-300 px-4 py-3 text-gray-600 font-mono text-sm">
                                {{ $hist->created_at->format('d M Y - H:i') }}
                            </td>
                            <td class="border border-gray-300 px-4 py-3">
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
                            <td class="border border-gray-300 px-4 py-3 {{ $hist->status === 'revision' ? 'text-red-600 font-medium' : 'text-gray-700' }}">
                                {{ $hist->note ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="border border-gray-300 px-4 py-10 text-center text-gray-400">
                                Belum ada riwayat tercatat untuk usulan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</section>
