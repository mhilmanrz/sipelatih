<x-layouts.app>
    <x-slot:title>Edit Kriteria Evaluasi</x-slot>

    <div class="px-8 py-6">
        <div class="mb-8">
            <a href="{{ route('evaluation-criteria.index') }}"
               class="inline-flex items-center gap-2 text-teal-600 hover:text-teal-700 text-sm font-medium mb-6">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar Kriteria
            </a>

            <h1 class="text-3xl font-bold text-gray-900">
                Edit Kriteria Evaluasi
            </h1>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <strong>Terdapat kesalahan:</strong>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 max-w-2xl">
            <form action="{{ route('evaluation-criteria.update', $criterion->id) }}" method="POST" class="space-y-6"
                x-data="criteriaForm">
                @csrf
                @method('PUT')

                {{-- TINGKAT EVALUASI --}}
                <div>
                    <label for="evaluation_type" class="block text-sm font-semibold text-gray-700 mb-2">
                        Tingkat Evaluasi <span class="text-red-600">*</span>
                    </label>
                    <select id="evaluation_type" name="evaluation_type" required
                        x-model="evaluationType" @change="onTypeChange"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('evaluation_type') border-red-600 @enderror">
                        <option value="">-- Pilih Tingkat Evaluasi --</option>
                        <option value="1">Level 1</option>
                        <option value="3">Level 3</option>
                    </select>
                    @error('evaluation_type')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- SCOPE / TARGET EVALUASI --}}
                <div x-show="evaluationType === '1'" x-transition>
                    <label for="form_type" class="block text-sm font-semibold text-gray-700 mb-2">
                        Scope / Target Evaluasi <span class="text-red-600">*</span>
                    </label>
                    <select id="form_type" name="form_type" x-model="formType"
                        :required="evaluationType === '1'"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('form_type') border-red-600 @enderror">
                        <option value="">-- Pilih Scope --</option>
                        <option value="speaker">Narasumber</option>
                        <option value="activity">Kegiatan</option>
                    </select>
                    @error('form_type')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- KATEGORI --}}
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label for="evaluation_category_id" class="block text-sm font-semibold text-gray-700">
                            Kategori <span class="text-red-600" x-show="evaluationType === '1' || evaluationType === '3'">*</span>
                        </label>
                        <a href="{{ route('evaluation-categories.create') }}" target="_blank" class="text-xs text-teal-600 hover:text-teal-700 hover:underline flex items-center gap-1">
                            <i class="fas fa-plus"></i> Tambah Kategori
                        </a>
                    </div>
                    <select id="evaluation_category_id" name="evaluation_category_id"
                        x-model="categoryId"
                        :disabled="!(evaluationType === '1' || evaluationType === '3')"
                        :required="evaluationType === '1' || evaluationType === '3'"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('evaluation_category_id') border-red-600 @enderror disabled:bg-gray-100 disabled:text-gray-500">
                        <option value="" x-show="!(evaluationType === '1' || evaluationType === '3')">-- Pilih Tingkat Evaluasi Terlebih Dahulu --</option>
                        <option value="" x-show="evaluationType === '1' || evaluationType === '3'">-- Pilih Kategori --</option>
                        <template x-for="cat in filteredCategories" :key="cat.id">
                            <option :value="cat.id" x-text="cat.name"></option>
                        </template>
                    </select>
                    @error('evaluation_category_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- KODE KRITERIA --}}
                <div>
                    <label for="code" class="block text-sm font-semibold text-gray-700 mb-2">
                        Kode Kriteria <span class="text-red-600">*</span>
                    </label>
                    <input type="text" id="code" name="code" value="{{ old('code', $criterion->code) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('code') border-red-600 @enderror">
                    @error('code')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- NAMA KRITERIA --}}
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Kriteria <span class="text-red-600">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $criterion->name) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('name') border-red-600 @enderror">
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- TIPE KRITERIA --}}
                <div>
                    <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">
                        Tipe Kriteria <span class="text-red-600">*</span>
                    </label>
                    <select id="type" name="type" required x-model="criteriaType"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('type') border-red-600 @enderror">
                        <option value="rating" @selected(old('type', $criterion->type) === 'rating')>Rating 1-4</option>
                        <option value="isian" @selected(old('type', $criterion->type) === 'isian')>Isian (Teks)</option>
                    </select>
                    @error('type')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>



                {{-- URUTAN --}}
                <div>
                    <label for="order" class="block text-sm font-semibold text-gray-700 mb-2">
                        Urutan <span class="text-gray-500 text-sm font-normal">(Opsional)</span>
                    </label>
                    <input type="number" id="order" name="order" value="{{ old('order', $criterion->order) }}" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('order') border-red-600 @enderror">
                    @error('order')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="px-6 py-3 bg-teal-600 text-white rounded-lg font-medium hover:bg-teal-700 transition">
                        Perbarui Kriteria
                    </button>
                    <a href="{{ route('evaluation-criteria.index') }}"
                       class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        var criteriaCategories = @json($categories);
        var criteriaInitType = String(@json(old('evaluation_type', $criterion->evaluation_type)));
        var criteriaInitCategoryId = @json(old('evaluation_category_id', $criterion->evaluation_category_id)) || '';
        var criteriaInitFormType = @json(old('form_type', $criterion->form_type)) || '';

        var criteriaInitTypeOption = @json(old('type', $criterion->type ?? 'rating'));

        document.addEventListener('alpine:init', function () {
            Alpine.data('criteriaForm', function () {
                return {
                    criteriaType: criteriaInitTypeOption,
                    evaluationType: criteriaInitType,
                    categoryId: criteriaInitCategoryId,
                    formType: criteriaInitFormType,
                    categories: criteriaCategories,
                    get filteredCategories() {
                        return this.categories.filter(function (c) {
                            return String(c.evaluation_type) === String(this.evaluationType);
                        }.bind(this));
                    },
                    get selectedCategory() {
                        if (!this.categoryId) return null;
                        var id = this.categoryId;
                        return this.categories.find(function (c) {
                            return String(c.id) === String(id);
                        }) || null;
                    },
                    onTypeChange: function () {
                        this.categoryId = '';
                        this.formType = '';
                    },
                };
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            // Remove vanilla JS event listener for is_fillable
        });
    </script>
    @endpush
</x-layouts.app>
