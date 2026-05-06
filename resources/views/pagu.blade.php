<x-layouts.app>
    <x-slot:title>Manajemen Pagu Anggaran</x-slot:title>

    <div class="p-6">
        <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
            <x-page-title>Manajemen Pagu Anggaran</x-page-title>

            <div class="flex items-center gap-4">


                <a href="{{ route('pagu.import.page') }}" id="btnImportPagu"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded shadow whitespace-nowrap text-sm"
                    style="text-decoration:none;">
                    Import Pagu
                </a>
                <button type="button" id="btnTambahPagu"
                    class="bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 px-4 rounded shadow whitespace-nowrap text-sm">
                    + Tambah Pagu
                </button>
            </div>
        </div>

        <!-- CHART AREA -->
        @if($totalDana > 0)
        <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center mb-6">
            <h2 class="text-xl font-bold text-gray-700 mb-4 text-center">Persentase Penyerapan Dana Pagu</h2>
            <div class="relative w-full max-w-md" style="height: 250px;">
                <canvas id="paguPieChart"></canvas>
            </div>
            <div class="mt-4 text-center text-sm text-gray-600">
                <p>Total Pagu: Rp {{ number_format($totalDana, 0, ',', '.') }}</p>
                <p>Pagu Digunakan: Rp {{ number_format($totalTerserap, 0, ',', '.') }}</p>
                <p>Pagu Tersisa: Rp {{ number_format($totalSisa, 0, ',', '.') }}</p>
            </div>
        </div>
        @endif

        @if(count($rkaklLabels) > 0)
        <!-- BAR CHART AREA per RKAKL -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-700 mb-4 text-center">Penyerapan Pagu per RKAKL (Tahun {{ $chartYear }})</h2>
            <div class="relative w-full" style="height: 350px;">
                <canvas id="paguBarChart"></canvas>
            </div>
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

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow">
            <x-table-toolbar actionUrl="{{ route('pagu.index') }}" searchPlaceholder="Cari RKAKL...">
                <div class="flex items-center gap-2">
                    <span class="text-sm">Filter Tahun:</span>
                    <select name="year" onchange="this.form.submit()" class="px-2 py-1 text-sm bg-transparent border border-white rounded outline-none text-white w-32">
                        <option value="" class="text-black">-- Semua --</option>
                        @foreach($availableYears as $y)
                            <option value="{{ $y }}" class="text-black" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
            </x-table-toolbar>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" style="min-width:700px;">
                    <thead class="bg-[#007a7a] border border-white py-3 px-4 font-semibold">
                        <tr>
                            <th scope="col" class="text-center w-12 border border-white py-3 px-4 font-semibold">No.</th>
                            <th scope="col" class="text-left border border-white py-3 px-4 font-semibold">No. RKAKL</th>
                            <th scope="col" class="text-left border border-white py-3 px-4 font-semibold">Tahun</th>
                            <th scope="col" class="text-left border border-white py-3 px-4 font-semibold">Kategori Pagu</th>
                            <th scope="col" class="text-left border border-white py-3 px-4 font-semibold">Submark</th>
                            <th scope="col" class="text-right border border-white py-3 px-4 font-semibold">Pagu Awal</th>
                            <th scope="col" class="text-right border border-white py-3 px-4 font-semibold">Blokir</th>
                            <th scope="col" class="text-right border border-white py-3 px-4 font-semibold">Pagu Efektif</th>
                            <th scope="col" class="text-right border border-white py-3 px-4 font-semibold">Realisasi Pagu</th>
                            <th scope="col" class="text-right border border-white py-3 px-4 font-semibold">Sisa Pagu</th>
                            <th scope="col" class="text-center w-40 border border-white py-3 px-4 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $sumPaguAwal   = 0;
                            $sumBlokir     = 0;
                            $sumEfektif    = 0;
                            $sumRealisasi  = 0;
                            $sumSisa       = 0;
                        @endphp
                        @forelse($budgets as $index => $budget)
                            @php
                                $sumPaguAwal  += $budget->total_amount;
                                $sumBlokir    += $budget->blokir;
                                $sumEfektif   += $budget->efektif_amount;
                                $sumRealisasi += $budget->realisasi_amount;
                                $sumSisa      += $budget->sisa_amount;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="whitespace-nowrap text-sm text-center text-gray-500 border border-gray-200 py-3 px-4">
                                    {{ $budgets->firstItem() + $loop->index }}
                                </td>
                                <td class="whitespace-nowrap text-sm font-medium text-gray-900 border border-gray-200 py-3 px-4">
                                    {{ $budget->rkkal_code }}
                                </td>
                                <td class="whitespace-nowrap text-sm text-gray-900 border border-gray-200 py-3 px-4">
                                    {{ $budget->year }}
                                </td>
                                <td class="whitespace-nowrap text-sm text-gray-900 border border-gray-200 py-3 px-4">
                                    {{ $budget->budgetCategory->name ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap text-sm text-gray-900 border border-gray-200 py-3 px-4">
                                    {{ $budget->submark }}
                                </td>
                                <td class="whitespace-nowrap text-sm text-right text-gray-900 border border-gray-200 py-3 px-4">
                                    {{ number_format($budget->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="whitespace-nowrap text-sm text-right border border-gray-200 py-3 px-4
                                    {{ $budget->blokir > 0 ? 'text-red-600 font-semibold' : 'text-gray-400' }}">
                                    {{ number_format($budget->blokir, 0, ',', '.') }}
                                </td>
                                <td class="whitespace-nowrap text-sm text-right text-gray-900 font-medium border border-gray-200 py-3 px-4">
                                    {{ number_format($budget->efektif_amount, 0, ',', '.') }}
                                </td>
                                <td class="whitespace-nowrap text-sm text-right border border-gray-200 py-3 px-4
                                    {{ $budget->realisasi_amount > 0 ? 'text-blue-700 font-medium' : 'text-gray-400' }}">
                                    {{ number_format($budget->realisasi_amount, 0, ',', '.') }}
                                </td>
                                <td class="whitespace-nowrap text-sm text-right font-semibold border border-gray-200 py-3 px-4
                                    {{ $budget->sisa_amount < 0 ? 'text-red-600' : 'text-green-700' }}">
                                    {{ number_format($budget->sisa_amount, 0, ',', '.') }}
                                </td>
                                <td class="whitespace-nowrap text-sm font-medium text-center space-x-1 flex justify-center border border-gray-200 py-3 px-4">
                                    <a href="{{ route('pagu.show', $budget->id) }}" class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-600 hover:bg-green-100 border border-green-200 rounded text-sm font-medium transition-colors" style="text-decoration:none;">
                                        Detail
                                    </a>
                                    <button type="button" class="btn-edit inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 border border-blue-200 rounded text-sm font-medium transition-colors"
                                            data-id="{{ $budget->id }}"
                                            data-rkkal="{{ $budget->rkkal_code }}"
                                            data-year="{{ $budget->year }}"
                                            data-category="{{ $budget->budget_category_id }}"
                                            data-submark="{{ $budget->submark }}"
                                            data-amount="{{ $budget->total_amount }}"
                                            data-blokir="{{ $budget->blokir }}">
                                        Edit
                                    </button>
                                    <form action="{{ route('pagu.destroy', $budget->id) }}" method="POST" class="inline-block m-0 h-full">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus pagu ini?')" style="background-color: #ef4444;" class="text-white px-3 py-1.5 rounded hover:bg-[#dc2626] text-sm font-semibold transition inline-block">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-gray-500 text-sm border border-gray-200 py-8 px-4">
                                    Belum ada data Pagu.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-gray-100 font-bold border-t-2 border-gray-300">
                        <tr>
                            <td colspan="5" class="text-right text-sm text-gray-800 uppercase border border-gray-200 py-3 px-4">
                                Total
                            </td>
                            <td class="text-right text-sm text-gray-800 border border-gray-200 py-3 px-4">
                                {{ number_format($sumPaguAwal, 0, ',', '.') }}
                            </td>
                            <td class="text-right text-sm text-red-600 border border-gray-200 py-3 px-4">
                                {{ number_format($sumBlokir, 0, ',', '.') }}
                            </td>
                            <td class="text-right text-sm text-gray-800 border border-gray-200 py-3 px-4">
                                {{ number_format($sumEfektif, 0, ',', '.') }}
                            </td>
                            <td class="text-right text-sm text-blue-700 border border-gray-200 py-3 px-4">
                                {{ number_format($sumRealisasi, 0, ',', '.') }}
                            </td>
                            <td class="text-right text-sm border border-gray-200 py-3 px-4 {{ $sumSisa < 0 ? 'text-red-600' : 'text-green-700' }}">
                                {{ number_format($sumSisa, 0, ',', '.') }}
                            </td>
                            <td class="border border-gray-200"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            @if(method_exists($budgets, 'links'))
                <x-table-footer :paginator="$budgets" />
            @endif
        </div>
    </div>

    <!-- TAILWIND MODAL OVERLAY -->
    <div id="modalPagu" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
        <!-- MODAL BOX -->
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4 overflow-hidden transform transition-all">
            <form id="formPagu" method="POST" action="{{ route('pagu.store') }}">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="bg-teal-600 px-6 py-4 flex justify-between items-center text-white">
                    <h2 id="modalTitle" class="text-lg font-bold">PENGATURAN PAGU ANGGARAN</h2>
                    <button type="button" class="text-white hover:text-gray-200 focus:outline-none text-2xl" id="closeModal">&times;</button>
                </div>

                    <div class="p-6 space-y-4 text-left">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">No. RKAKL <span class="text-red-500">*</span></label>
                                <input type="text" name="rkkal_code" id="inputRkkal" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Anggaran <span class="text-red-500">*</span></label>
                                <input type="number" name="year" id="inputYear" required min="2000"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500 shadow-sm"
                                       placeholder="Contoh: {{ date('Y') }}">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Pagu <span class="text-red-500">*</span></label>
                                <select name="budget_category_id" id="inputCategory" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                                    <option value="">-- Pilih Kategori Pagu --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Submark</label>
                                <input type="text" name="submark" id="inputSubmark"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pagu Awal <span class="text-red-500">*</span></label>
                                <input type="number" name="total_amount" id="inputAmount" required min="0"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500 shadow-sm"
                                       placeholder="0">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Blokir</label>
                                <input type="number" name="blokir" id="inputBlokir" min="0" value="0"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500 shadow-sm"
                                       placeholder="0">
                                <p class="text-xs text-gray-400 mt-1">Isi jika ada dana yang diblokir/dibekukan.</p>
                            </div>
                        </div>
                    </div>

                <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3 border-t border-gray-200">
                    <button type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 font-medium" id="btnCancel">BATAL</button>
                    <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 font-medium shadow">SIMPAN</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <!-- Inline Javascript for Modal Logic -->
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const modal = document.getElementById('modalPagu');
                const btnTambah = document.getElementById('btnTambahPagu');
                const btnClose = document.getElementById('closeModal');
                const btnCancel = document.getElementById('btnCancel');
                const formPagu = document.getElementById('formPagu');
                const formMethod = document.getElementById('formMethod');
                const modalTitle = document.getElementById('modalTitle');

                // Form Inputs
                const inputRkkal    = document.getElementById('inputRkkal');
                const inputYear     = document.getElementById('inputYear');
                const inputCategory = document.getElementById('inputCategory');
                const inputSubmark  = document.getElementById('inputSubmark');
                const inputAmount   = document.getElementById('inputAmount');
                const inputBlokir   = document.getElementById('inputBlokir');

                function hideModal() {
                    modal.classList.add('hidden');
                }

                function showModal() {
                    modal.classList.remove('hidden');
                }

                // Open Modal for Tambah
                btnTambah.addEventListener('click', () => {
                    showModal();
                    modalTitle.innerText = "TAMBAH PAGU ANGGARAN";
                    formPagu.action = "{{ route('pagu.store') }}";
                    formMethod.value = "POST";

                    inputRkkal.value    = '';
                    inputYear.value     = new Date().getFullYear();
                    inputCategory.value = '';
                    inputSubmark.value  = '';
                    inputAmount.value   = '';
                    inputBlokir.value   = '0';
                });

                btnClose.addEventListener('click', hideModal);
                btnCancel.addEventListener('click', hideModal);

                // Open Modal for Edit
                document.querySelectorAll('.btn-edit').forEach(btn => {
                    btn.addEventListener('click', function() {
                        showModal();
                        modalTitle.innerText = "EDIT PAGU ANGGARAN";

                        const id       = this.getAttribute('data-id');
                        const rkkal    = this.getAttribute('data-rkkal');
                        const year     = this.getAttribute('data-year');
                        const category = this.getAttribute('data-category');
                        const submark  = this.getAttribute('data-submark');
                        const amount   = this.getAttribute('data-amount');
                        const blokir   = this.getAttribute('data-blokir');

                        formPagu.action  = `/pagu/${id}`;
                        formMethod.value = "PUT";

                        inputRkkal.value    = rkkal;
                        inputYear.value     = year;
                        inputCategory.value = category;
                        inputSubmark.value  = submark;
                        inputAmount.value   = amount;
                        inputBlokir.value   = blokir ?? '0';
                    });
                });

                window.onclick = function(event) {
                    if (event.target == modal) {
                        hideModal();
                    }
                }
            });
        </script>

        @if($totalDana > 0)
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('paguPieChart').getContext('2d');

                const data = {
                    labels: ['Dana Digunakan', 'Dana Tersisa'],
                    datasets: [{
                        label: 'Serapan Pagu',
                        data: [
                            {{ $totalTerserap ?? 0 }},
                            {{ $totalSisa < 0 ? 0 : $totalSisa }}
                        ],
                        backgroundColor: [
                            '#3b82f6', // blue-500
                            '#10b981'  // emerald-500
                        ],
                        borderColor: [
                            '#2563eb', // blue-600
                            '#059669'  // emerald-600
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
                                        label += percentage + '% (Rp ' + new Intl.NumberFormat('id-ID').format(value) + ')';
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            });
        </script>
        @endif

        @if(count($rkaklLabels) > 0)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const barCtx = document.getElementById('paguBarChart').getContext('2d');

                const barData = {
                    labels: {!! json_encode($rkaklLabels) !!},
                    datasets: [
                        {
                            label: 'Dana Digunakan',
                            data: {!! json_encode($rkaklDigunakan) !!},
                            backgroundColor: '#3b82f6', // blue-500
                            borderColor: '#2563eb', // blue-600
                            borderWidth: 1
                        },
                        {
                            label: 'Dana Tersisa',
                            data: {!! json_encode($rkaklSisa) !!},
                            backgroundColor: '#10b981', // emerald-500
                            borderColor: '#059669', // emerald-600
                            borderWidth: 1
                        }
                    ]
                };

                new Chart(barCtx, {
                    type: 'bar',
                    data: barData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                stacked: true,
                            },
                            y: {
                                stacked: true,
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value, index, values) {
                                        if (value >= 1000000000) {
                                            return (value / 1000000000) + ' M';
                                        } else if (value >= 1000000) {
                                            return (value / 1000000) + ' Jt';
                                        }
                                        return new Intl.NumberFormat('id-ID').format(value);
                                    }
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        let value = context.raw;
                                        label += 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            });
        </script>
        @endif
    @endpush
</x-layouts.app>
