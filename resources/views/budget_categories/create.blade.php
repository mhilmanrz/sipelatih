<x-layouts.app>
    <x-slot:title>Tambah Kategori Pagu</x-slot>

    @push('styles')
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            .tw-wrap p, .tw-wrap h1, .tw-wrap h2, .tw-wrap h3, .tw-wrap h4, .tw-wrap h5, .tw-wrap h6, .tw-wrap span, .tw-wrap div, .tw-wrap a, .tw-wrap button, .tw-wrap form, .tw-wrap input, .tw-wrap label {
                font-family: inherit;
            }
        </style>
    @endpush

    <div class="tw-wrap p-6 max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-white border-b pb-2">TAMBAH KATEGORI PAGU</h1>
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
            <form action="{{ route('budget-categories.store') }}" method="POST">
                @csrf
                <div class="mb-5">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori Pagu <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500 shadow-sm"
                           placeholder="Contoh: Studi Banding Pegawai Internal">
                </div>

                <div class="mb-5">
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Kode (Opsional)</label>
                    <input type="text" name="code" id="code" value="{{ old('code') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500 shadow-sm"
                           placeholder="Bila ada kode khusus untuk integrasi master data">
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                    <a href="{{ route('budget-categories.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 transition-colors shadow">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
