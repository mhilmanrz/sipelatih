<x-layouts.app>
    <x-slot:title>Edit Kriteria Evaluasi</x-slot>

    @push('styles')
        <style>
            .tw-wrap p, .tw-wrap h1, .tw-wrap h2, .tw-wrap h3, .tw-wrap h4, .tw-wrap h5, .tw-wrap h6, .tw-wrap span, .tw-wrap div, .tw-wrap a, .tw-wrap button, .tw-wrap form, .tw-wrap input, .tw-wrap label {
                font-family: inherit;
            }
        </style>
    @endpush

    <div class="tw-wrap p-6 max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-white border-b pb-2">EDIT KRITERIA EVALUASI</h1>
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

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('evaluation-criteria.update', $criterion->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- TINGKAT EVALUASI --}}
                <div class="mb-5">
                    <label for="evaluation_type" class="block text-sm font-medium text-gray-700 mb-2">Tingkat Evaluasi <span class="text-red-500">*</span></label>
                    <select name="evaluation_type" id="evaluation_type" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500 shadow-sm @error('evaluation_type') border-red-500 @enderror">
                        <option value="">Pilih Tingkat Evaluasi</option>
                        <option value="1" {{ old('evaluation_type', $criterion->evaluation_type) == '1' ? 'selected' : '' }}>Evaluasi 1 (Penyelenggaraan)</option>
                        <option value="2" {{ old('evaluation_type', $criterion->evaluation_type) == '2' ? 'selected' : '' }}>Evaluasi 2 (Hasil Belajar)</option>
                        <option value="3" {{ old('evaluation_type', $criterion->evaluation_type) == '3' ? 'selected' : '' }}>Evaluasi 3 (Dampak)</option>
                    </select>
                    @error('evaluation_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- KODE KRITERIA --}}
                <div class="mb-5">
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Kode Kriteria <span class="text-red-500">*</span></label>
                    <input type="text" name="code" id="code" value="{{ old('code', $criterion->code) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500 shadow-sm @error('code') border-red-500 @enderror">
                    @error('code')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- NAMA KRITERIA --}}
                <div class="mb-5">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Kriteria <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $criterion->name) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500 shadow-sm @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- FILLABLE / BISA DIISI NILAI --}}
                <div class="mb-5">
                    <div class="flex items-center">
                        <input id="is_fillable" name="is_fillable" type="checkbox" value="1" {{ old('is_fillable', $criterion->is_fillable) ? 'checked' : '' }}
                            class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                        <label for="is_fillable" class="ml-2 block text-sm text-gray-900 font-medium">
                            Kriteria Butuh Input Nilai Tambahan? (Jika centang, form evaluasi akan menampilkan input isian)
                        </label>
                    </div>
                </div>

                {{-- TIPE INPUT (STRING / NUMBER) --}}
                <div id="type_container" class="mb-5 {{ old('is_fillable', $criterion->is_fillable) ? '' : 'hidden' }}">
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Isian Nilai <span class="text-red-500">*</span></label>
                    <select name="type" id="type"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500 shadow-sm @error('type') border-red-500 @enderror">
                        <option value="string" {{ old('type', $criterion->type) == 'string' ? 'selected' : '' }}>Teks (String)</option>
                        <option value="number" {{ old('type', $criterion->type) == 'number' ? 'selected' : '' }}>Angka (Number)</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ORDER / URUTAN --}}
                <div class="mb-5">
                    <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Urutan Tampil <span class="text-red-500">*</span></label>
                    <input type="number" name="order" id="order" value="{{ old('order', $criterion->order) }}" required min="0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500 shadow-sm @error('order') border-red-500 @enderror">
                    @error('order')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                    <a href="{{ route('evaluation-criteria.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 transition-colors shadow">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const isFillableCheckbox = document.getElementById('is_fillable');
                const typeContainer = document.getElementById('type_container');

                isFillableCheckbox.addEventListener('change', function () {
                    if (this.checked) {
                        typeContainer.classList.remove('hidden');
                    } else {
                        typeContainer.classList.add('hidden');
                    }
                });
            });
        </script>
    @endpush
</x-layouts.app>
