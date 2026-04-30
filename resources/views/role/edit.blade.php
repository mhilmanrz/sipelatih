<x-layouts.app>
    <x-slot:title>Edit Role</x-slot>

@push('styles')
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .tw-wrap p, .tw-wrap h1, .tw-wrap h2, .tw-wrap h3, .tw-wrap h4, .tw-wrap h5, .tw-wrap h6,
        .tw-wrap span, .tw-wrap div, .tw-wrap a, .tw-wrap button, .tw-wrap input, .tw-wrap label {
            font-family: inherit;
        }
    </style>
@endpush

    <div class="tw-wrap p-6 max-w-2xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('roles.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                <i class="fa-solid fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Edit Role</h1>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <strong class="font-bold">Terjadi kesalahan!</strong>
                <ul class="list-disc pl-5 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Role</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" required
                        class="mt-1 focus:ring-teal-500 focus:border-teal-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2 border"
                        placeholder="Contoh: Manajer, IT Support">
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('roles.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded shadow hover:bg-gray-300">
                        Batal
                    </a>
                    <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded shadow hover:bg-teal-700">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
