<x-layouts.app>
    <x-slot:title>Tambah Ruang Lingkup</x-slot>

    @push('styles')
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
            .tw-wrap label,
            .tw-wrap input {
                font-family: inherit;
            }
        </style>
    @endpush

    <div class="tw-wrap p-6 max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-white">TAMBAH RUANG LINGKUP BARU</h1>
            <p class="text-gray-300 mt-1">Silakan isi form di bawah ini untuk menambahkan data ruang lingkup baru.</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Terjadi Kesalahan!</strong>
                <ul class="list-disc mt-2 ml-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('activity-scopes.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="code" class="block text-gray-700 font-semibold mb-2">Kode</label>
                    <input type="text" name="code" id="code" value="{{ old('code') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 @error('code') border-red-500 @enderror"
                        placeholder="Kode (opsional)">
                    @error('code')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>


                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-semibold mb-2">Nama Ruang Lingkup <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 @error('name') border-red-500 @enderror"
                        placeholder="Contoh: Internal RSCM" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-8 flex items-center justify-end space-x-4">
                    <a href="{{ route('activity-scopes.index') }}"
                        class="text-gray-600 hover:text-gray-800 font-medium px-4 py-2 border border-gray-300 rounded shadow-sm hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 px-6 rounded shadow transition-colors">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
