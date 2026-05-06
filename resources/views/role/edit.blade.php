<x-layouts.app>
    @section('title', 'Edit Role')

    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        <x-page-title>Edit Role</x-page-title>
        <a href="{{ route('roles.index') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 bg-white border border-gray-300 px-4 py-2 rounded-lg transition">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
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

    <div class="max-w-xl bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('roles.update', $role->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Role <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                    placeholder="Contoh: Manajer, IT Support">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white font-semibold px-5 py-2 rounded-lg text-sm transition">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                </button>
                <a href="{{ route('roles.index') }}"
                    class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-5 py-2 rounded-lg text-sm transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-layouts.app>
