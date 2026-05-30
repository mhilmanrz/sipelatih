<x-layouts.app>
    <x-slot:title>Edit Kategori Evaluasi</x-slot>

    <div class="px-8 py-6">
        <div class="mb-8">
            <a href="{{ route('evaluation-categories.index') }}"
               class="inline-flex items-center gap-2 text-teal-600 hover:text-teal-700 text-sm font-medium mb-6">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar Kategori
            </a>

            <h1 class="text-3xl font-bold text-gray-900">
                Edit Kategori Evaluasi
            </h1>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 max-w-2xl">
            <form action="{{ route('evaluation-categories.update', $evaluationCategory->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Kategori <span class="text-red-600">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $evaluationCategory->name) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('name') border-red-600 @enderror"
                        placeholder="Misal: Kualitas Penyampaian Materi">
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="evaluation_type" class="block text-sm font-semibold text-gray-700 mb-2">
                        Tipe Evaluasi <span class="text-red-600">*</span>
                    </label>
                    <select id="evaluation_type" name="evaluation_type"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('evaluation_type') border-red-600 @enderror">
                        <option value="">-- Pilih Tipe Evaluasi --</option>
                        <option value="1" @selected(old('evaluation_type', $evaluationCategory->evaluation_type) == 1)>Level 1 (Kepuasan Peserta)</option>
                        <option value="3" @selected(old('evaluation_type', $evaluationCategory->evaluation_type) == 3)>Level 3 (Penilaian Dampak)</option>
                    </select>
                    @error('evaluation_type')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="form_type" class="block text-sm font-semibold text-gray-700 mb-2">
                        Tipe Form <span class="text-gray-500 text-sm">(Opsional)</span>
                    </label>
                    <select id="form_type" name="form_type"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('form_type') border-red-600 @enderror">
                        <option value="">-- Tidak Ada (Berlaku untuk semua) --</option>
                        <option value="speaker" @selected(old('form_type', $evaluationCategory->form_type) === 'speaker')>Narasumber</option>
                        <option value="activity" @selected(old('form_type', $evaluationCategory->form_type) === 'activity')>Kegiatan</option>
                    </select>
                    @error('form_type')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-600 text-sm mt-2">
                        Tipe form hanya relevan untuk Level 1. Abaikan untuk Level 3.
                    </p>
                </div>

                <div>
                    <label for="order" class="block text-sm font-semibold text-gray-700 mb-2">
                        Urutan <span class="text-gray-500 text-sm">(Opsional)</span>
                    </label>
                    <input type="number" id="order" name="order" value="{{ old('order', $evaluationCategory->order) }}" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('order') border-red-600 @enderror">
                    @error('order')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" class="px-6 py-3 bg-teal-600 text-white rounded-lg font-medium hover:bg-teal-700 transition">
                        Perbarui Kategori
                    </button>
                    <a href="{{ route('evaluation-categories.index') }}"
                       class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
