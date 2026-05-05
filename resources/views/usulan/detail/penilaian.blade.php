<section>
    <div class="mb-6">

        

        @php
            $statuses = $kegiatan->statuses()->orderBy('created_at', 'desc')->get();
        @endphp

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse border border-gray-200">
                <thead>
                    <tr>
                        <th class="bg-[#007a7a] text-white text-center w-16 py-3 px-4 font-semibold text-sm">NO.</th>
                        <th class="bg-[#007a7a] text-white text-left w-48 py-3 px-4 font-semibold text-sm">Tanggal Waktu</th>
                        <th class="bg-[#007a7a] text-white text-left w-48 py-3 px-4 font-semibold text-sm">Status Mutasi</th>
                        <th class="bg-[#007a7a] text-white text-left py-3 px-4 font-semibold text-sm">Keterangan / Catatan Reviewer</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($statuses as $index => $hist)
                        <tr class="hover:bg-gray-50">
                            <td class="text-center text-gray-900 text-sm border border-gray-200 py-3 px-4">
                                {{ $statuses->count() - $index }} 
                            </td>
                            <td class="text-gray-900 font-mono text-sm border border-gray-200 py-3 px-4">
                                {{ $hist->created_at->format('d M Y - H:i') }}
                            </td>
                            <td class="border border-gray-200 py-3 px-4">
                                @if($hist->status === 'draft')
                                    <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded text-xs font-bold uppercase tracking-wide">
                                        ✎ Draft
                                    </span>
                                @elseif($hist->status === 'submitted')
                                    <span class="bg-blue-500 text-white px-3 py-1 rounded text-xs font-bold uppercase shadow-sm tracking-wide">
                                        🚀 Terkirim / Diajukan
                                    </span>
                                @elseif($hist->status === 'revision')
                                    <span class="bg-yellow-500 text-white px-3 py-1 rounded text-xs font-bold uppercase shadow-sm tracking-wide">
                                        🔧 Butuh Perbaikan
                                    </span>
                                @elseif($hist->status === 'accepted')
                                    <span class="bg-green-500 text-white px-3 py-1 rounded text-xs font-bold uppercase shadow-sm tracking-wide">
                                        ✔ Disetujui
                                    </span>
                                @else
                                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded text-xs font-bold uppercase">
                                        {{ $hist->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="{{ $hist->status === 'revision' ? 'text-red-600 font-medium' : 'text-gray-900' }} border border-gray-200 py-3 px-4">
                                {{ $hist->note ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-gray-400 border border-gray-200 py-3 px-4">
                                Belum ada riwayat tercatat untuk usulan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</section>
