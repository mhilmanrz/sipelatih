@extends('layout.LayoutSuperAdmin')

@section('content')
    <div class="bg-[#13b9c6] min-h-screen font-sans pb-8">
        <!-- TITLE & BUTTON -->
        <section class="px-8 py-6 flex flex-wrap justify-between items-center gap-4">
            <h1 class="text-white text-3xl font-bold">Tambah Jabatan</h1>
            <a href="{{ route('positions.index') }}"
                class="inline-flex items-center gap-2 bg-white text-gray-700 px-5 py-2.5 rounded-full font-bold shadow hover:bg-gray-50 transition">
                Kembali
            </a>
        </section>

        <section class="mx-8 bg-white rounded-[20px] overflow-hidden shadow p-8">
            <form action="{{ route('positions.store') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label for="code" class="block text-gray-700 font-semibold mb-2">Kode Jabatan <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="code" name="code" value="{{ old('code') }}" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#007a7a] @error('code') border-red-500 @enderror">
                    @error('code')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="name" class="block text-gray-700 font-semibold mb-2">Nama Jabatan <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#007a7a] @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end mt-8">
                    <button type="submit"
                        class="bg-[#007a7a] text-white px-6 py-2.5 rounded-full font-bold shadow hover:bg-[#006bd6] transition">
                        Simpan Data
                    </button>
                </div>
            </form>
        </section>
    </div>
@endsection
