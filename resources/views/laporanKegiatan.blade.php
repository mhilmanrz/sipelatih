<x-layouts.app>
    @section('title', 'Laporan Kegiatan')

    {{-- HEADER --}}
    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        <x-page-title>LAPORAN KEGIATAN</x-page-title>
        <div class="flex flex-wrap items-center gap-3">
            <form action="{{ route('kegiatan.laporan.index') }}" method="GET" class="flex items-center gap-2">
                <select name="year" id="year" onchange="this.form.submit()"
                    class="bg-white border border-gray-300 rounded-full px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-teal-200 text-gray-800">
                    @php
                        $currentYear = date('Y');
                        $startYear = 2020;
                    @endphp
                    @for ($y = $currentYear + 1; $y >= $startYear; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
            <a href="{{ route('kegiatan.laporan.template') }}"
                class="inline-flex items-center justify-center text-black px-5 py-2.5 rounded-full font-bold shadow transition bg-[#D6DE20] hover:opacity-85"
                id="btnDownloadTemplate">
                <i class="fas fa-download mr-2"></i> Download Template
            </a>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="relative px-4 py-3 mb-4 text-green-700 bg-green-100 border border-green-400 rounded" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <strong>Terdapat kesalahan:</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- PIE CHART --}}
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 flex flex-col items-center">
        <h3 class="mb-4 border-b border-gray-100 pb-3 text-lg font-semibold text-gray-800 w-full text-center">
            Persentase Usulan Kegiatan Berdasarkan Status
        </h3>
        <div class="relative w-full max-w-md" style="height: 250px;">
            <canvas id="statusPieChart"></canvas>
        </div>
        <p class="text-sm text-gray-500 mt-4">Total Kegiatan: {{ $totalActivities }}</p>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <x-table>
            <x-slot name="header">
                <tr>
                    <th class="px-4 py-3 text-center w-12">NO.</th>
                    <th class="px-4 py-3 text-left">Nama Kegiatan</th>
                    <th class="px-4 py-3 text-center">Tgl Mulai</th>
                    <th class="px-4 py-3 text-center">Tgl Selesai</th>
                    <th class="px-4 py-3 text-center">Jml Peserta</th>
                    <th class="px-4 py-3 text-center">Cakupan Kegiatan</th>
                    <th class="px-4 py-3 text-center">JP Total Materi</th>
                    <th class="px-4 py-3 text-center">Kategori Diklat</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Laporan</th>
                </tr>
            </x-slot>

            @forelse($activities as $index => $item)
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
                <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                    <td class="text-center border border-gray-200 py-3 px-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                    <td class="border border-gray-200 py-3 px-4 text-sm text-gray-900 font-medium">
                        {{ $item->activityName->name ?? ($item->reference_number ?? '-') }}</td>
                    <td class="text-center border border-gray-200 py-3 px-4 text-sm text-gray-900">{{ $item->start_date ?? '-' }}</td>
                    <td class="text-center border border-gray-200 py-3 px-4 text-sm text-gray-900">{{ $item->end_date ?? '-' }}</td>
                    <td class="text-center border border-gray-200 py-3 px-4 text-sm text-gray-900 font-semibold">
                        {{ $item->activityParticipants->count() }}</td>
                    <td class="text-center border border-gray-200 py-3 px-4 text-sm text-gray-900">
                        {{ $item->activityScope->name ?? '-' }}</td>
                    <td class="text-center border border-gray-200 py-3 px-4 text-sm text-gray-900 font-semibold">
                        {{ $item->activityMaterials->sum('value') }} JP</td>
                    <td class="text-center border border-gray-200 py-3 px-4 text-sm text-gray-900">
                        {{ $item->activityCategory->name ?? '-' }}</td>
                    <td class="text-center border border-gray-200 py-3 px-4">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badge['class'] }}">
                            {{ $badge['label'] }}
                        </span>
                    </td>
                    <td class="text-center border border-gray-200 py-3 px-4 whitespace-nowrap">
                        @if ($item->report)
                            <div class="flex justify-center gap-1">
                                <button
                                    onclick="openModal({{ $item->id }}, {{ $item->report->id }}, '{{ addslashes($item->activityName->name ?? ($item->reference_number ?? '-')) }}')"
                                    class="text-white px-3 py-1.5 rounded text-sm font-semibold transition hover:opacity-85"
                                    style="background-color: #eab308;">
                                    <i class="fas fa-edit"></i> Ubah
                                </button>
                                <a href="{{ Storage::url($item->report->file_path) }}" target="_blank"
                                    class="text-white px-3 py-1.5 rounded text-sm font-semibold transition hover:opacity-85"
                                    style="background-color: #3b82f6;">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                            </div>
                        @else
                            <button
                                onclick="openModal({{ $item->id }}, null, '{{ addslashes($item->activityName->name ?? ($item->reference_number ?? '-')) }}')"
                                class="inline-flex items-center justify-center bg-[#1A5555] hover:opacity-85 text-white font-bold px-3 py-1.5 rounded shadow transition text-sm">
                                <i class="fas fa-upload mr-1"></i> Upload
                            </button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center text-gray-500 text-sm border border-gray-200 py-6 px-4">
                        Tidak ada data kegiatan.
                    </td>
                </tr>
            @endforelse
        </x-table>
    </div>

    {{-- UPLOAD/EDIT MODAL (Guide-compliant) --}}
    <div id="laporanModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4 overflow-hidden">
            <form id="laporanForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="methodContainer"></div>

                {{-- Header --}}
                <div class="bg-teal-600 px-6 py-4 flex justify-between items-center text-white">
                    <h2 id="modalTitle" class="text-lg font-bold">Upload Laporan</h2>
                    <button type="button" class="text-white hover:text-gray-200 text-2xl" onclick="closeModal()">&times;</button>
                </div>

                {{-- Body --}}
                <div class="p-6 space-y-4 text-left">
                    <input type="hidden" name="activity_id" id="activity_id">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kegiatan</label>
                        <input type="text" id="activity_name" readonly
                            class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 cursor-not-allowed shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">File Laporan <span class="text-red-500">*</span></label>
                        <input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500 shadow-sm text-sm">
                        <p class="text-xs text-gray-500 mt-1">Maksimal ukuran file: 10MB (PDF, DOC/X, XLS/X)</p>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3 border-t border-gray-200">
                    <button type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 font-medium" onclick="closeModal()">BATAL</button>
                    <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 font-medium shadow">SIMPAN</button>
                </div>
            </form>
        </div>
    </div>

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
                    labels: ['Draft', 'Submitted', 'Revision', 'Accepted'],
                    datasets: [{
                        label: 'Jumlah Kegiatan',
                        data: [
                            {{ $statusCounts['draft'] ?? 0 }},
                            {{ $statusCounts['submitted'] ?? 0 }},
                            {{ $statusCounts['revision'] ?? 0 }},
                            {{ $statusCounts['accepted'] ?? 0 }}
                        ],
                        backgroundColor: [
                            '#e5e7eb', // gray-200 for draft
                            '#dbeafe', // blue-100 for submitted
                            '#fef08a', // yellow-200 for revision
                            '#bbf7d0'  // green-200 for accepted
                        ],
                        borderColor: [
                            '#9ca3af', // gray-400
                            '#3b82f6', // blue-500
                            '#eab308', // yellow-500
                            '#22c55e'  // green-500
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
                                    font: {
                                        size: 14
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
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
