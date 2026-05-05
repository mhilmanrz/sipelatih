<x-layouts.app>
    <x-slot:title>Laporan Kegiatan</x-slot>

    <div class="px-8 py-6">

        {{-- TITLE & BUTTONS --}}
        <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
            <x-page-title>Laporan Kegiatan</x-page-title>
            <a href="{{ route('kegiatan.laporan.template') }}"
                class="inline-flex items-center justify-center gap-2 bg-white border border-[#007a7a] text-[#007a7a] px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#007a7a] hover:text-white transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Download Template
            </a>
        </div>

        {{-- FILTER BAR --}}
        <form method="GET" action="{{ route('kegiatan.laporan.index') }}"
            class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="flex flex-wrap items-center gap-3 px-5 py-4 border-b border-gray-200">

                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <span>Tampilkan</span>
                    <select name="entries" onchange="this.form.submit()"
                        class="bg-gray-50 border border-gray-300 rounded-lg px-2.5 py-1.5 text-sm text-gray-700 outline-none focus:ring-2 focus:ring-[#007a7a]/40 focus:border-[#007a7a] transition">
                        <option value="5" {{ request('entries') == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('entries', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('entries') == 50 ? 'selected' : '' }}>50</option>
                    </select>
                    <span>data</span>
                </div>

                <div class="h-6 w-px bg-gray-200 hidden sm:block"></div>

                <select name="year" onchange="this.form.submit()"
                    class="bg-gray-50 border border-gray-300 rounded-lg px-3 py-1.5 text-sm text-gray-700 outline-none focus:ring-2 focus:ring-[#007a7a]/40 focus:border-[#007a7a] transition appearance-none pr-8 bg-[url('data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22%236b7280%22%3E%3Cpath%20fill-rule%3D%22evenodd%22%20d%3D%22M5.23%207.21a.75.75%200%20011.06.02L10%2011.168l3.71-3.938a.75.75%200%20111.08%201.04l-4.25%204.5a.75.75%200%2001-1.08%200l-4.25-4.5a.75.75%200%2001.02-1.06z%22%20clip-rule%3D%22evenodd%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem] bg-[right_0.5rem_center] bg-no-repeat">
                    @foreach($availableYears as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        {{-- ALERTS --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <strong>Terdapat kesalahan:</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        {{-- CHART AREA --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-base font-semibold text-gray-800">Persentase Usulan Kegiatan Berdasarkan Status</h3>
            </div>
            <div class="p-6 flex flex-col items-center">
                <div class="relative w-full max-w-md" style="height: 250px;">
                    <canvas id="statusPieChart"></canvas>
                </div>
                <p class="mt-4 text-sm text-gray-600">Total Kegiatan: <span class="font-semibold">{{ $totalActivities }}</span></p>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse" style="min-width: 900px;">
                    <thead class="bg-[#007a7a] text-white">
                        <tr>
                            <th class="text-center w-12 py-3 px-4 font-semibold text-sm">No.</th>
                            <th class="text-left py-3 px-4 font-semibold text-sm">Nama Kegiatan</th>
                            <th class="text-center py-3 px-4 font-semibold text-sm">Tgl Mulai</th>
                            <th class="text-center py-3 px-4 font-semibold text-sm">Tgl Selesai</th>
                            <th class="text-center py-3 px-4 font-semibold text-sm">Jml Peserta</th>
                            <th class="text-center py-3 px-4 font-semibold text-sm">Cakupan</th>
                            <th class="text-center py-3 px-4 font-semibold text-sm">JP Materi</th>
                            <th class="text-center py-3 px-4 font-semibold text-sm">Kategori</th>
                            <th class="text-center py-3 px-4 font-semibold text-sm">Status</th>
                            <th class="text-center w-40 py-3 px-4 font-semibold text-sm">Laporan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($paginatedActivities as $index => $item)
                            @php
                                $status = $item->latestStatus?->status ?? 'draft';
                                $statusMap = [
                                    'draft'     => ['label' => 'Draft',     'class' => 'bg-gray-200 text-gray-700'],
                                    'submitted' => ['label' => 'Diajukan',  'class' => 'bg-blue-100 text-blue-700'],
                                    'revision'  => ['label' => 'Revisi',    'class' => 'bg-yellow-100 text-yellow-700'],
                                    'accepted'  => ['label' => 'Disetujui', 'class' => 'bg-green-100 text-green-700'],
                                ];
                                $badge = $statusMap[$status] ?? ['label' => ucfirst($status), 'class' => 'bg-gray-100 text-gray-600'];
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="text-center py-3 px-4">{{ $paginatedActivities->firstItem() + $index }}</td>
                                <td class="font-medium py-3 px-4">{{ $item->activityName->name ?? ($item->reference_number ?? '-') }}</td>
                                <td class="text-center py-3 px-4">{{ $item->start_date ?? '-' }}</td>
                                <td class="text-center py-3 px-4">{{ $item->end_date ?? '-' }}</td>
                                <td class="text-center font-semibold py-3 px-4">{{ $item->activityParticipants->count() }}</td>
                                <td class="text-center py-3 px-4">{{ $item->activityScope->name ?? '-' }}</td>
                                <td class="text-center font-semibold py-3 px-4">{{ $item->activityMaterials->sum('value') }} JP</td>
                                <td class="text-center py-3 px-4">{{ $item->activityCategory->name ?? '-' }}</td>
                                <td class="text-center py-3 px-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $badge['class'] }}">
                                        {{ $badge['label'] }}
                                    </span>
                                </td>
                                <td class="text-center py-3 px-4">
                                    <div class="flex justify-center gap-1.5">
                                        @if ($item->report)
                                            <button type="button"
                                                onclick="openModal({{ $item->id }}, {{ $item->report->id }}, '{{ addslashes($item->activityName->name ?? ($item->reference_number ?? '-')) }}')"
                                                class="inline-flex items-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-xs font-semibold transition">
                                                <i class="fas fa-edit mr-1"></i> Ubah
                                            </button>
                                            <a href="{{ Storage::url($item->report->file_path) }}" target="_blank"
                                                class="inline-flex items-center px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-xs font-semibold transition">
                                                <i class="fas fa-eye mr-1"></i> Lihat
                                            </a>
                                        @else
                                            <button type="button"
                                                onclick="openModal({{ $item->id }}, null, '{{ addslashes($item->activityName->name ?? ($item->reference_number ?? '-')) }}')"
                                                class="inline-flex items-center px-3 py-1.5 bg-[#007a7a] hover:bg-[#005f5f] text-white rounded-lg text-xs font-semibold transition">
                                                <i class="fas fa-upload mr-1"></i> Upload
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-gray-500 py-6 px-4">
                                    Tidak ada data kegiatan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="px-5 py-4 border-t border-gray-200">
                {{ $paginatedActivities->links('components.pagination') }}
            </div>
        </div>
    </div>

    {{-- MODAL --}}
    <div id="laporanModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg mx-4 overflow-hidden">
            <form id="laporanForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="methodContainer"></div>

                <input type="hidden" name="activity_id" id="activity_id">

                <div class="bg-[#007a7a] px-6 py-4 flex justify-between items-center">
                    <h2 id="modalTitle" class="text-lg font-bold text-white">Upload Laporan</h2>
                    <button type="button" class="text-white hover:text-gray-200 focus:outline-none text-2xl" onclick="closeModal()">&times;</button>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kegiatan</label>
                        <input type="text" id="activity_name"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm bg-gray-100 cursor-not-allowed"
                            readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">File Laporan <span class="text-red-500">*</span></label>
                        <input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-[#007a7a]/40 focus:border-[#007a7a] transition">
                        <p class="text-xs text-gray-500 mt-1">Maksimal ukuran file: 10MB (PDF, DOC/X, XLS/X)</p>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 border-t border-gray-200">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium text-sm transition">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-[#007a7a] text-white rounded-lg hover:bg-[#005f5f] font-medium text-sm transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @endpush

    @push('scripts')
        <script>
            function openModal(activityId, reportId, activityName) {
                document.getElementById('activity_id').value = activityId;
                document.getElementById('activity_name').value = activityName;

                let form = document.getElementById('laporanForm');
                let methodContainer = document.getElementById('methodContainer');
                let modalTitle = document.getElementById('modalTitle');

                if (reportId) {
                    modalTitle.innerText = "Ubah Laporan";
                    form.action = "{{ url('laporan-kegiatan') }}/" + reportId;
                    methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';
                } else {
                    modalTitle.innerText = "Upload Laporan";
                    form.action = "{{ route('kegiatan.laporan.store') }}";
                    methodContainer.innerHTML = '';
                }

                document.getElementById('laporanModal').classList.remove('hidden');
            }

            function closeModal() {
                document.getElementById('laporanModal').classList.add('hidden');
                document.getElementById('laporanForm').reset();
            }

            window.onclick = function(event) {
                let modal = document.getElementById('laporanModal');
                if (event.target == modal) {
                    closeModal();
                }
            }
        </script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('statusPieChart').getContext('2d');

                const data = {
                    labels: ['Draft', 'Diajukan', 'Revisi', 'Disetujui'],
                    datasets: [{
                        label: 'Jumlah Kegiatan',
                        data: [
                            {{ $statusCounts['draft'] ?? 0 }},
                            {{ $statusCounts['submitted'] ?? 0 }},
                            {{ $statusCounts['revision'] ?? 0 }},
                            {{ $statusCounts['accepted'] ?? 0 }}
                        ],
                        backgroundColor: [
                            'rgba(156, 163, 175, 0.5)',
                            'rgba(59, 130, 246, 0.5)',
                            'rgba(234, 179, 8, 0.5)',
                            'rgba(34, 197, 94, 0.5)'
                        ],
                        borderColor: [
                            '#9ca3af',
                            '#3b82f6',
                            '#eab308',
                            '#22c55e'
                        ],
                        borderWidth: 1
                    }]
                };

                new Chart(ctx, {
                    type: 'pie',
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    font: { size: 14 }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        if (label) { label += ': '; }
                                        let value = context.raw;
                                        let total = context.chart._metasets[context.datasetIndex].total;
                                        let percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                        label += value + ' (' + percentage + '%)';
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
</x-layouts.app>