<x-layouts.app>
    <x-slot:title>Tambah Narasumber/Moderator Eksternal</x-slot:title>

    <div class="p-6 max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-white">TAMBAH NARASUMBER/MODERATOR EKSTERNAL</h1>
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
            <form action="{{ route('external-persons.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-semibold mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" required>
                </div>

                <div class="mb-4">
                    <label for="nik" class="block text-gray-700 font-semibold mb-2">NIK</label>
                    <input type="text" name="nik" id="nik" value="{{ old('nik') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="mb-4">
                    <label for="employee_id" class="block text-gray-700 font-semibold mb-2">NIP (jika ada)</label>
                    <input type="text" name="employee_id" id="employee_id" value="{{ old('employee_id') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="mb-4">
                    <label for="npwp" class="block text-gray-700 font-semibold mb-2">NPWP</label>
                    <input type="text" name="npwp" id="npwp" value="{{ old('npwp') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="mb-4">
                    <label for="institution" class="block text-gray-700 font-semibold mb-2">Instansi Asal</label>
                    <input type="text" name="institution" id="institution" value="{{ old('institution') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="mb-4">
                    <label for="external_position" class="block text-gray-700 font-semibold mb-2">Jabatan di Instansi Asal</label>
                    <input type="text" name="external_position" id="external_position" value="{{ old('external_position') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="mb-4">
                    <label for="phone_number" class="block text-gray-700 font-semibold mb-2">No HP</label>
                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-semibold mb-2">Email (opsional)</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Kapasitas <span class="text-red-500">*</span></label>
                    <label class="inline-flex items-center mr-6">
                        <input type="checkbox" name="is_narasumber" value="1" {{ old('is_narasumber') ? 'checked' : '' }} class="mr-2">
                        Narasumber
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_moderator" value="1" {{ old('is_moderator') ? 'checked' : '' }} class="mr-2">
                        Moderator
                    </label>
                </div>

                <div class="mt-8 flex items-center justify-end space-x-4">
                    <a href="{{ route('external-persons.index') }}"
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
