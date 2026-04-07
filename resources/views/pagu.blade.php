@extends('layout.LayoutSuperAdmin')

@section('title', 'Manajemen Pagu Anggaran')

@push('styles')
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .tw-wrap p,
        .tw-wrap h1,
        .tw-wrap h2,
        .tw-wrap h3,
        .tw-wrap h4,
        .tw-wrap h5,
        .tw-wrap h6,
        .tw-wrap span,
        .tw-wrap div,
        .tw-wrap a,
        .tw-wrap button,
        .tw-wrap table,
        .tw-wrap th,
        .tw-wrap td,
        .tw-wrap tr,
        .tw-wrap thead,
        .tw-wrap tbody,
        .tw-wrap form,
        .tw-wrap input,
        .tw-wrap label,
        .tw-wrap select {
            font-family: inherit;
        }
    </style>
@endpush

@section('content')
    <div class="tw-wrap p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Pagu Anggaran</h1>
            <button type="button" id="btnTambahPagu"
                class="bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 px-4 rounded shadow">
                + Tambah Pagu
            </button>
        </div>

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

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-teal-600">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider w-16">No.</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">No. RKAKL</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Kategori Pagu</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Submark</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-white uppercase tracking-wider">Pagu</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-white uppercase tracking-wider">Sisa Pagu</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider w-48">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php $sum = 0; @endphp
                    @forelse($budgets as $index => $budget)
                        @php $sum += $budget->total_amount; @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $budget->rkkal_code }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $budget->budgetCategory->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $budget->submark }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                                {{ number_format($budget->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                                {{ number_format($budget->remaining_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center space-x-2 flex justify-center">
                                <button type="button" class="btn-edit inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 border border-blue-200 rounded text-sm font-medium transition-colors"
                                        data-id="{{ $budget->id }}"
                                        data-rkkal="{{ $budget->rkkal_code }}"
                                        data-category="{{ $budget->budget_category_id }}"
                                        data-submark="{{ $budget->submark }}"
                                        data-amount="{{ $budget->total_amount }}">
                                    Edit
                                </button>
                                <form action="{{ route('pagu.destroy', $budget->id) }}" method="POST" class="inline-block m-0 h-full">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 rounded text-sm font-medium transition-colors"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus pagu ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500 text-sm">
                                Belum ada data Pagu.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-100 font-bold border-t-2 border-gray-300">
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-right text-sm text-gray-800 uppercase">
                            Total Pagu
                        </td>
                        <td class="px-6 py-4 text-right text-sm text-gray-800">
                            {{ number_format($sum, 0, ',', '.') }}
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- TAILWIND MODAL OVERLAY -->
    <div id="modalPagu" class="tw-wrap fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
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
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. RKAKL <span class="text-red-500">*</span></label>
                        <input type="text" name="rkkal_code" id="inputRkkal" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500 shadow-sm">
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

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Submark</label>
                        <input type="text" name="submark" id="inputSubmark"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pagu <span class="text-red-500">*</span></label>
                        <input type="text" id="inputAmountDisplay" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500 shadow-sm"
                               placeholder="Contoh: 50.000.000">
                        <input type="hidden" name="total_amount" id="inputAmount">
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3 border-t border-gray-200">
                    <button type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 font-medium" id="btnCancel">BATAL</button>
                    <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 font-medium shadow">SIMPAN</button>
                </div>
            </form>
        </div>
    </div>
@endsection

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
            const inputRkkal = document.getElementById('inputRkkal');
            const inputCategory = document.getElementById('inputCategory');
            const inputSubmark = document.getElementById('inputSubmark');
            const inputAmount = document.getElementById('inputAmount');
            const inputAmountDisplay = document.getElementById('inputAmountDisplay');

            // Function format angka ribuan
            function formatNumber(n) {
                if (!n) return '';
                return n.toString().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            // Keyup event formatting
            inputAmountDisplay.addEventListener('keyup', function(e) {
                this.value = formatNumber(this.value);
                inputAmount.value = this.value.replace(/\./g, "");
            });

            // Function to close modal
            function hideModal() {
                modal.classList.add('hidden');
            }

            // Function to open modal
            function showModal() {
                modal.classList.remove('hidden');
            }

            // Open Modal for Tambah
            btnTambah.addEventListener('click', () => {
                showModal();
                modalTitle.innerText = "TAMBAH PAGU ANGGARAN";
                formPagu.action = "{{ route('pagu.store') }}";
                formMethod.value = "POST";
                
                // Reset fields
                inputRkkal.value = '';
                inputCategory.value = '';
                inputSubmark.value = '';
                inputAmount.value = '';
                inputAmountDisplay.value = '';
            });

            // Close Modal bindings
            btnClose.addEventListener('click', hideModal);
            btnCancel.addEventListener('click', hideModal);

            // Open Modal for Edit
            document.querySelectorAll('.btn-edit').forEach(btn => {
                btn.addEventListener('click', function() {
                    showModal();
                    modalTitle.innerText = "EDIT PAGU ANGGARAN";
                    
                    // Get data from data-* attributes
                    const id = this.getAttribute('data-id');
                    const rkkal = this.getAttribute('data-rkkal');
                    const category = this.getAttribute('data-category');
                    const submark = this.getAttribute('data-submark');
                    const amount = this.getAttribute('data-amount');

                    // Set form action dynamically & add method spoofing for PUT
                    formPagu.action = `/pagu/${id}`;
                    formMethod.value = "PUT";

                    // Set input values
                    inputRkkal.value = rkkal;
                    inputCategory.value = category;
                    inputSubmark.value = submark;
                    inputAmount.value = amount;
                    inputAmountDisplay.value = formatNumber(amount);
                });
            });

            // Close when click outside modal
            window.onclick = function(event) {
                if (event.target == modal) {
                    hideModal();
                }
            }
        });
    </script>
@endpush
