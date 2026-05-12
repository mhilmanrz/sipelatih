<x-layouts.app>
    <x-slot:title>Manajemen Pagu Anggaran</x-slot>

    <div class="px-8 py-6">

        {{-- TITLE & BUTTONS --}}
        <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
            <x-page-title>Manajemen Pagu Anggaran</x-page-title>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('pagu.import.page') }}"
                    class="inline-flex items-center justify-center gap-2 bg-white border border-[#007a7a] text-[#007a7a] px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#007a7a] hover:text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12"></path>
                    </svg>
                    Import Pagu
                </a>
                <button type="button" id="btnTambahPagu"
                    class="inline-flex items-center justify-center gap-2 bg-[#007a7a] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#005f5f] active:bg-[#004d4d] transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Pagu
                </button>
            </div>
        </div>

        {{-- FILTER BAR --}}
        <form method="GET" action="{{ route('pagu.index') }}"
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
                    <option value="">Semua Tahun</option>
                    @foreach($availableYears as $y)
                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
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
        @if($totalDana > 0)
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-base font-semibold text-gray-800">Persentase Penyerapan Dana Pagu</h3>
            </div>
            <div class="p-6 flex flex-col items-center">
                <div class="relative w-full max-w-md" style="height: 250px;">
                    <canvas id="paguPieChart"></canvas>
                </div>
                <div class="mt-4 text-center text-sm text-gray-600">
                    <p>Total Pagu: <span class="font-semibold">Rp {{ number_format($totalDana, 0, ',', '.') }}</span></p>
                    <p>Pagu Digunakan: <span class="font-semibold">Rp {{ number_format($totalTerserap, 0, ',', '.') }}</span></p>
                    <p>Pagu Tersisa: <span class="font-semibold">Rp {{ number_format($totalSisa, 0, ',', '.') }}</span></p>
                </div>
            </div>
        </div>
        @endif

        @if(count($rkaklLabels) > 0)
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-base font-semibold text-gray-800">Penyerapan Pagu per RKAKL (Tahun {{ $chartYear }})</h3>
            </div>
            <div class="p-6" style="height: 350px;">
                <canvas id="paguBarChart"></canvas>
            </div>
        </div>
        @endif

        {{-- TABLE --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse" style="min-width: 700px;">
                    <thead class="bg-[#007a7a] text-white">
                        <tr>
                            <th class="text-center w-12 py-3 px-4 font-semibold text-sm">No.</th>
                            <th class="text-left py-3 px-4 font-semibold text-sm">No. RKAKL</th>
                            <th class="text-left py-3 px-4 font-semibold text-sm">Tahun</th>
                            <th class="text-left py-3 px-4 font-semibold text-sm">Kategori Pagu</th>
                            <th class="text-left py-3 px-4 font-semibold text-sm">Submark</th>
                            <th class="text-right py-3 px-4 font-semibold text-sm">Pagu</th>
                            <th class="text-right py-3 px-4 font-semibold text-sm">Dana Blokir</th>
                            <th class="text-right py-3 px-4 font-semibold text-sm">Sisa Pagu</th>
                            <th class="text-center w-48 py-3 px-4 font-semibold text-sm">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php $sum = 0; @endphp
                        @forelse($paginatedBudgets as $index => $budget)
                            @php $sum += $budget->total_amount; @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="text-center py-3 px-4">{{ $paginatedBudgets->firstItem() + $index }}</td>
                                <td class="font-medium py-3 px-4">{{ $budget->rkkal_code }}</td>
                                <td class="py-3 px-4">{{ $budget->year }}</td>
                                <td class="py-3 px-4">{{ $budget->budgetCategory->name ?? '-' }}</td>
                                <td class="py-3 px-4">{{ $budget->submark }}</td>
                                <td class="text-right font-medium py-3 px-4">{{ number_format($budget->total_amount, 0, ',', '.') }}</td>
                                <td class="text-right py-3 px-4">{{ number_format($budget->blocked_amount, 0, ',', '.') }}</td>
                                <td class="text-right py-3 px-4">{{ number_format($budget->remaining_amount, 0, ',', '.') }}</td>
                                <td class="text-center py-3 px-4">
                                    <div class="flex justify-center gap-1.5">
                                        <a href="{{ route('pagu.show', $budget->id) }}"
                                            class="inline-flex items-center px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-xs font-semibold transition">
                                            Detail
                                        </a>
                                        <button type="button" class="btn-edit inline-flex items-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-xs font-semibold transition"
                                                data-id="{{ $budget->id }}"
                                                data-rkkal="{{ $budget->rkkal_code }}"
                                                data-year="{{ $budget->year }}"
                                                data-category="{{ $budget->budget_category_id }}"
                                                data-submark="{{ $budget->submark }}"
                                                data-amount="{{ $budget->total_amount }}"
                                                data-blocked="{{ $budget->blocked_amount }}">
                                            Edit
                                        </button>
                                        <form action="{{ route('pagu.destroy', $budget->id) }}" method="POST" class="inline-block m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus pagu ini?')"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs font-semibold transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-gray-500 py-6 px-4">
                                    Belum ada data Pagu.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($paginatedBudgets->total() > 0)
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="5" class="text-right text-sm font-semibold text-gray-800 py-3 px-4 uppercase">
                                Total Pagu
                            </td>
                            <td class="text-right text-sm font-semibold text-gray-800 py-3 px-4">
                                {{ number_format($budgets->sum('total_amount'), 0, ',', '.') }}
                            </td>
                            <td class="text-right text-sm font-semibold text-gray-800 py-3 px-4">
                                {{ number_format($budgets->sum('blocked_amount'), 0, ',', '.') }}
                            </td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="px-5 py-4 border-t border-gray-200">
                {{ $paginatedBudgets->links('components.pagination') }}
            </div>
        </div>
    </div>

    {{-- MODAL --}}
    <div id="modalPagu" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg mx-4 overflow-hidden">
            <form id="formPagu" method="POST" action="{{ route('pagu.store') }}">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="bg-[#007a7a] px-6 py-4 flex justify-between items-center">
                    <h2 id="modalTitle" class="text-lg font-bold text-white">Pengaturan Pagu Anggaran</h2>
                    <button type="button" class="text-white hover:text-gray-200 focus:outline-none text-2xl" id="closeModal">&times;</button>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. RKAKL <span class="text-red-500">*</span></label>
                        <input type="text" name="rkkal_code" id="inputRkkal" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-[#007a7a]/40 focus:border-[#007a7a] transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Anggaran <span class="text-red-500">*</span></label>
                        <input type="number" name="year" id="inputYear" required min="2000"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-[#007a7a]/40 focus:border-[#007a7a] transition"
                               placeholder="Contoh: {{ date('Y') }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Pagu <span class="text-red-500">*</span></label>
                        <select name="budget_category_id" id="inputCategory" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-[#007a7a]/40 focus:border-[#007a7a] transition">
                            <option value="">-- Pilih Kategori Pagu --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Submark</label>
                        <input type="text" name="submark" id="inputSubmark"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-[#007a7a]/40 focus:border-[#007a7a] transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pagu <span class="text-red-500">*</span></label>
                        <input type="number" name="total_amount" id="inputAmount" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-[#007a7a]/40 focus:border-[#007a7a] transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dana Blokir</label>
                        <input type="number" name="blocked_amount" id="inputBlocked" min="0"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-[#007a7a]/40 focus:border-[#007a7a] transition"
                               placeholder="0">
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 border-t border-gray-200">
                    <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium text-sm transition" id="btnCancel">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-[#007a7a] text-white rounded-lg hover:bg-[#005f5f] font-medium text-sm transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const modal = document.getElementById('modalPagu');
                const btnTambah = document.getElementById('btnTambahPagu');
                const btnClose = document.getElementById('closeModal');
                const btnCancel = document.getElementById('btnCancel');
                const formPagu = document.getElementById('formPagu');
                const formMethod = document.getElementById('formMethod');
                const modalTitle = document.getElementById('modalTitle');

                const inputRkkal = document.getElementById('inputRkkal');
                const inputYear = document.getElementById('inputYear');
                const inputCategory = document.getElementById('inputCategory');
                const inputSubmark = document.getElementById('inputSubmark');
                const inputAmount = document.getElementById('inputAmount');
                const inputBlocked = document.getElementById('inputBlocked');

                function hideModal() {
                    modal.classList.add('hidden');
                }

                function showModal() {
                    modal.classList.remove('hidden');
                }

                btnTambah.addEventListener('click', () => {
                    showModal();
                    modalTitle.innerText = "TAMBAH PAGU ANGGARAN";
                    formPagu.action = "{{ route('pagu.store') }}";
                    formMethod.value = "POST";

                    inputRkkal.value = '';
                    inputYear.value = new Date().getFullYear();
                    inputCategory.value = '';
                    inputSubmark.value = '';
                    inputAmount.value = '';
                    inputBlocked.value = '';
                });

                btnClose.addEventListener('click', hideModal);
                btnCancel.addEventListener('click', hideModal);

                document.querySelectorAll('.btn-edit').forEach(btn => {
                    btn.addEventListener('click', function() {
                        showModal();
                        modalTitle.innerText = "EDIT PAGU ANGGARAN";

                        const id = this.getAttribute('data-id');
                        const rkkal = this.getAttribute('data-rkkal');
                        const year = this.getAttribute('data-year');
                        const category = this.getAttribute('data-category');
                        const submark = this.getAttribute('data-submark');
                        const amount = this.getAttribute('data-amount');
                        const blocked = this.getAttribute('data-blocked');

                        formPagu.action = `/pagu/${id}`;
                        formMethod.value = "PUT";

                        inputRkkal.value = rkkal;
                        inputYear.value = year;
                        inputCategory.value = category;
                        inputSubmark.value = submark;
                        inputAmount.value = amount;
                        inputBlocked.value = blocked;
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
                            'rgba(0, 122, 122, 0.7)',
                            'rgba(107, 114, 128, 0.5)'
                        ],
                        borderColor: [
                            'rgba(0, 122, 122, 1)',
                            'rgba(107, 114, 128, 1)'
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
                            backgroundColor: 'rgba(0, 122, 122, 0.7)',
                            borderColor: 'rgba(0, 122, 122, 1)',
                            borderWidth: 1,
                            borderRadius: 4
                        },
                        {
                            label: 'Dana Tersisa',
                            data: {!! json_encode($rkaklSisa) !!},
                            backgroundColor: 'rgba(107, 114, 128, 0.5)',
                            borderColor: 'rgba(107, 114, 128, 1)',
                            borderWidth: 1,
                            borderRadius: 4
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
                            x: { stacked: true },
                            y: {
                                stacked: true,
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        if (value >= 1000000000) return (value / 1000000000) + ' M';
                                        if (value >= 1000000) return (value / 1000000) + ' Jt';
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
                                        if (label) { label += ': '; }
                                        label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
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