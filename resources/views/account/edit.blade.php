<x-layouts.app>
    <x-slot:title>Edit Akun</x-slot>

    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold text-white">EDIT AKUN</h1>
        <a href="{{ route('accounts.index') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 bg-white border border-gray-300 px-4 py-2 rounded-lg transition">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="max-w-xl bg-white rounded-xl shadow-sm p-6">

        @if($errors->any())
            <div class="mb-4 px-4 py-3 bg-red-50 border border-red-300 text-red-700 rounded-lg text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('accounts.update', $user->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Unit Kerja (Scope)</label>
                <select name="work_unit_id"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                    <option value="">-- Semua Unit --</option>
                    @foreach ($workUnits as $wu)
                        <option value="{{ $wu->id }}"
                            {{ old('work_unit_id', $user->work_unit_id) == $wu->id ? 'selected' : '' }}>
                            {{ $wu->name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1">Biarkan kosong jika akun ini memiliki akses ke semua unit.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role Sistem <span class="text-red-500">*</span></label>
                <select name="role" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                    <option value="">-- Pilih Role --</option>
                    @php $currentRole = $user->roles->first()->name ?? ''; @endphp
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}"
                            {{ old('role', $currentRole) == $role->name ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="border-t border-gray-100 pt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengganti"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                <p class="text-xs text-gray-400 mt-1">Minimal 8 karakter. Kosongkan jika tidak ingin mengubah password.</p>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white font-semibold px-5 py-2 rounded-lg text-sm transition">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan
                </button>
                <a href="{{ route('accounts.index') }}"
                    class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-5 py-2 rounded-lg text-sm transition">
                    Batal
                </a>
            </div>
        </form>
    </div>

</x-layouts.app>
