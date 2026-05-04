<x-layouts.app>
@section('title', 'Pengaturan Aplikasi')

    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        <x-page-title>Pengaturan Aplikasi</x-page-title>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-800 rounded-lg flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- NAMA APLIKASI --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-teal-600"></i>
                    Nama Aplikasi
                </h2>

                <label class="block text-sm text-gray-600 mb-1">Nama Aplikasi</label>
                <input type="text" name="app_name"
                    value="{{ old('app_name', $settings->get('app_name', 'siPELATIH')) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                    placeholder="siPELATIH" required>
                @error('app_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-400 mt-2">Nama ini ditampilkan pada tab browser dan elemen lain di aplikasi.</p>
            </div>

            {{-- LOGO APLIKASI --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-image text-teal-600"></i>
                    Logo Aplikasi
                </h2>

                {{-- Preview logo saat ini --}}
                @if($settings->get('app_logo'))
                    <div class="mb-3 flex items-center gap-4">
                        <img src="{{ asset('storage/' . $settings->get('app_logo')) }}"
                            alt="Logo saat ini" class="h-16 object-contain rounded border border-gray-200 p-1 bg-gray-50">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Logo saat ini</p>
                            <a href="{{ route('settings.delete-logo') }}"
                                onclick="return confirm('Hapus logo ini?')"
                                class="text-xs text-red-500 hover:underline">
                                <i class="fa-solid fa-trash mr-1"></i>Hapus Logo
                            </a>
                        </div>
                    </div>
                @else
                    <div class="mb-3 flex items-center gap-4">
                        <img src="{{ asset('assets/images/logo-sipelatih.png') }}"
                            alt="Logo default" class="h-16 object-contain rounded border border-gray-200 p-1 bg-gray-50">
                        <p class="text-xs text-gray-400">Menggunakan logo default</p>
                    </div>
                @endif

                <label class="block text-sm text-gray-600 mb-1">Upload Logo Baru</label>
                <input type="file" name="app_logo" accept="image/png,image/jpeg,image/svg+xml,image/webp"
                    class="w-full text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100"
                    onchange="previewImage(this, 'logo-preview')">
                <img id="logo-preview" src="#" alt="Preview" class="hidden mt-3 h-16 object-contain rounded border border-gray-200 p-1">
                @error('app_logo')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-400 mt-2">Format: PNG, JPG, SVG, WebP. Maks 2MB.</p>
            </div>

            {{-- GAMBAR HALAMAN LOGIN --}}
            <div class="bg-white rounded-xl shadow-sm p-6 lg:col-span-2">
                <h2 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-right-to-bracket text-teal-600"></i>
                    Gambar Halaman Login
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                    <div>
                        {{-- Preview gambar login saat ini --}}
                        @if($settings->get('login_image'))
                            <div class="mb-3">
                                <p class="text-xs text-gray-500 mb-2">Gambar saat ini</p>
                                <img src="{{ asset('storage/' . $settings->get('login_image')) }}"
                                    alt="Gambar login" class="w-full max-h-48 object-cover rounded-lg border border-gray-200">
                                <a href="{{ route('settings.delete-login-image') }}"
                                    onclick="return confirm('Hapus gambar login ini?')"
                                    class="inline-block mt-2 text-xs text-red-500 hover:underline">
                                    <i class="fa-solid fa-trash mr-1"></i>Hapus Gambar
                                </a>
                            </div>
                        @else
                            <div class="mb-3 flex items-center justify-center h-32 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                                <p class="text-xs text-gray-400">Belum ada gambar login</p>
                            </div>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Upload Gambar Login Baru</label>
                        <input type="file" name="login_image" accept="image/png,image/jpeg,image/webp"
                            class="w-full text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100"
                            onchange="previewImage(this, 'login-preview')">
                        <img id="login-preview" src="#" alt="Preview" class="hidden mt-3 w-full max-h-48 object-cover rounded-lg border border-gray-200">
                        @error('login_image')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-400 mt-2">Format: PNG, JPG, WebP. Maks 4MB. Gambar ini ditampilkan sebagai background di halaman login.</p>
                    </div>
                </div>
            </div>

            {{-- LOGO KEMENKES --}}
            <div class="bg-white rounded-xl shadow-sm p-6 lg:col-span-2">
                <h2 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-hospital text-teal-600"></i>
                    Logo Kemenkes / RSCM (Untuk Form Kegiatan)
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                    <div>
                        {{-- Preview logo kemenkes saat ini --}}
                        @if($settings->get('kemenkes_logo'))
                            <div class="mb-3">
                                <p class="text-xs text-gray-500 mb-2">Logo saat ini</p>
                                <img src="{{ asset('storage/' . $settings->get('kemenkes_logo')) }}"
                                    alt="Logo Kemenkes" class="w-full max-h-48 object-contain rounded-lg border border-gray-200 bg-gray-50 p-2">
                                <a href="{{ route('settings.delete-kemenkes-logo') }}"
                                    onclick="return confirm('Hapus logo ini?')"
                                    class="inline-block mt-2 text-xs text-red-500 hover:underline">
                                    <i class="fa-solid fa-trash mr-1"></i>Hapus Logo
                                </a>
                            </div>
                        @else
                            <div class="mb-3 flex items-center justify-center h-32 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                                <p class="text-xs text-gray-400">Belum ada logo Kemenkes/RSCM</p>
                            </div>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Upload Logo Baru</label>
                        <input type="file" name="kemenkes_logo" accept="image/png,image/jpeg,image/svg+xml,image/webp"
                            class="w-full text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100"
                            onchange="previewImage(this, 'kemenkes-preview')">
                        <img id="kemenkes-preview" src="#" alt="Preview" class="hidden mt-3 w-full max-h-48 object-contain rounded-lg border border-gray-200 bg-gray-50 p-2">
                        @error('kemenkes_logo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-400 mt-2">Format: PNG, JPG, SVG, WebP. Maks 2MB. Logo ini akan ditampilkan pada header Formulir Permintaan Kegiatan.</p>
                    </div>
                </div>
            </div>

        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit"
                class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white font-semibold px-6 py-2.5 rounded-lg transition">
                <i class="fa-solid fa-floppy-disk"></i>
                Simpan Pengaturan
            </button>
        </div>
    </form>

@push('scripts')
<script>
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush

</x-layouts.app>
