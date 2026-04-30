    <x-layouts.app>
        <x-slot:title>Laporan Kegiatan</x-slot>

    @push('styles')
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="{{ asset('assets/css/LayoutSuperAdmin.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <style>
            .modal {
                display: none;
                position: fixed;
                z-index: 1000;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgba(0, 0, 0, 0.5);
            }

            .modal-content {
                background-color: #fefefe;
                margin: 10% auto;
                padding: 24px;
                border: 1px solid #888;
                width: 90%;
                max-width: 500px;
                border-radius: 8px;
                position: relative;
            }

            .close {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
                cursor: pointer;
            }

            .close:hover,
            .close:focus {
                color: black;
                text-decoration: none;
            }
        </style>
    @endpush

    <div class="p-8">
        <div class="mb-6 flex justify-between items-center">
            <x-page-title>LAPORAN KEGIATAN</x-page-title>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="bg-white overflow-hidden shadow"
            style="border-radius: 20px; margin-bottom: 24px; padding: 24px; display: flex; flex-direction: column; align-items: center;">
            <h2 style="font-size: 1.25rem; font-weight: bold; color: #374151; margin-bottom: 16px; text-align: center;">
                Persentase Usulan Kegiatan Berdasarkan Status</h2>
            <div style="position: relative; width: 100%; max-width: 400px; height: 250px;">
                <canvas id="statusPieChart"></canvas>
            </div>
            <p style="font-size: 0.875rem; color: #6b7280; margin-top: 16px;">Total Kegiatan: {{ $totalActivities }}</p>
        </section>

        <div class="bg-white rounded-xl shadow p-6 mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Daftar Laporan Kegiatan</h2>
                <div class="flex flex-wrap items-center gap-3">
                    <form action="{{ route('kegiatan.laporan.index') }}" method="GET" class="flex items-center gap-2">
                        <label for="year" class="font-semibold text-gray-700 whitespace-nowrap text-sm">Tahun:</label>
                        <select name="year" id="year"
                            class="border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-teal-200">
                            @php
                                $currentYear = date('Y');
                                $startYear = 2020;
                            @endphp
                            @for ($y = $currentYear + 1; $y >= $startYear; $y--)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        <button type="submit"
                            class="bg-teal-600 hover:bg-teal-700 text-white font-semibold px-4 py-2 rounded text-sm transition-colors">
                            Tampilkan
                        </button>
                    </form>
                    <a href="{{ route('kegiatan.laporan.template') }}"
                        class="inline-flex py-2 px-4 bg-gray-800 hover:bg-gray-900 text-white font-semibold rounded shadow items-center transition-colors text-sm">
                        <i class="fas fa-download mr-2"></i> Download Template
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead class="bg-[#007a7a] border border-white py-3 px-4 font-semibold">
                        <tr class="bg-gray-100 text-gray-700">
                            <th class="-300 text-center w-10 border border-white py-3 px-4 font-semibold">NO.</th>
                            <th class="-300 border border-white py-3 px-4 font-semibold">Nama Kegiatan</th>
                            <th class="-300 text-center border border-white py-3 px-4 font-semibold">Tgl Mulai</th>
                            <th class="-300 text-center border border-white py-3 px-4 font-semibold">Tgl Selesai</th>
                            <th class="-300 text-center border border-white py-3 px-4 font-semibold">Jml Peserta</th>
                            <th class="-300 text-center border border-white py-3 px-4 font-semibold">Cakupan Kegiatan</th>
                            <th class="-300 text-center border border-white py-3 px-4 font-semibold">JP Total Materi</th>
                            <th class="-300 text-center border border-white py-3 px-4 font-semibold">Kategori Diklat</th>
                            <th class="-300 text-center border border-white py-3 px-4 font-semibold">Status</th>
                            <th class="-300 text-center border border-white py-3 px-4 font-semibold">Laporan</th>
                        </tr>
                    </thead>
                    <tbody>
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
                            <tr class="hover:bg-gray-50">
                                <td class="-300 text-center border border-gray-200 py-3 px-4">{{ $index + 1 }}</td>
                                <td class="-300 font-medium border border-gray-200 py-3 px-4">
                                    {{ $item->activityName->name ?? ($item->reference_number ?? '-') }}</td>
                                <td class="-300 text-center border border-gray-200 py-3 px-4">{{ $item->start_date ?? '-' }}</td>
                                <td class="-300 text-center border border-gray-200 py-3 px-4">{{ $item->end_date ?? '-' }}</td>
                                <td class="-300 text-center font-semibold border border-gray-200 py-3 px-4">
                                    {{ $item->activityParticipants->count() }}</td>
                                <td class="-300 text-center border border-gray-200 py-3 px-4">
                                    {{ $item->activityScope->name ?? '-' }}</td>
                                <td class="-300 text-center font-semibold border border-gray-200 py-3 px-4">
                                    {{ $item->activityMaterials->sum('value') }} JP</td>
                                <td class="-300 text-center border border-gray-200 py-3 px-4">
                                    {{ $item->activityCategory->name ?? '-' }}</td>
                                <td class="-300 text-center border border-gray-200 py-3 px-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $badge['class'] }}">
                                        {{ $badge['label'] }}
                                    </span>
                                </td>
                                <td class="-300 text-center whitespace-nowrap border border-gray-200 py-3 px-4">
                                    @if ($item->report)
                                        <button
                                            onclick="openModal({{ $item->id }}, {{ $item->report->id }}, '{{ addslashes($item->activityName->name ?? ($item->reference_number ?? '-')) }}')"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs mr-1">
                                            <i class="fas fa-edit"></i> Ubah
                                        </button>
                                        <a href="{{ Storage::url($item->report->file_path) }}" target="_blank"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs inline-block">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                    @else
                                        <button
                                            onclick="openModal({{ $item->id }}, null, '{{ addslashes($item->activityName->name ?? ($item->reference_number ?? '-')) }}')"
                                            class="bg-teal-500 hover:bg-teal-600 text-white px-3 py-1 rounded text-xs">
                                            <i class="fas fa-upload"></i> Upload
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="-300 text-center text-gray-500 border border-gray-200 py-3 px-4">
                                    Tidak ada data kegiatan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Upload/Edit Modal -->
    <div id="laporanModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle" class="text-xl font-bold mb-4">Upload Laporan</h2>

            <form id="laporanForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="methodContainer"></div>

                <input type="hidden" name="activity_id" id="activity_id">

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Kegiatan</label>
                    <input type="text" id="activity_name"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight bg-gray-100 cursor-not-allowed"
                        readonly>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">File Laporan</label>
                    <input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx" required
                        class="w-full text-gray-700 border rounded py-2 px-3">
                    <p class="text-xs text-gray-500 mt-1">Maksimal ukuran file: 10MB (PDF, DOC/X, XLS/X)</p>
                </div>

                <div class="flex justify-end">
                    <button type="button" onclick="closeModal()"
                        class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded mr-2">
                        Batal
                    </button>
                    <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">
                        Simpan
                    </button>
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
                    // Edit mode
                    modalTitle.innerText = "Ubah Laporan";
                    form.action = "{{ url('laporan-kegiatan') }}/" + reportId;
                    methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';
                } else {
                    // Upload mode
                    modalTitle.innerText = "Upload Laporan";
                    form.action = "{{ route('kegiatan.laporan.store') }}";
                    methodContainer.innerHTML = '';
                }

                document.getElementById('laporanModal').style.display = 'block';
            }

            function closeModal() {
                document.getElementById('laporanModal').style.display = 'none';
                document.getElementById('laporanForm').reset();
            }

            // Close modal when clicking outside
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
                            '#bbf7d0' // green-200 for accepted
                        ],
                        borderColor: [
                            '#9ca3af', // gray-400
                            '#3b82f6', // blue-500
                            '#eab308', // yellow-500
                            '#22c55e' // green-500
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
