<x-layouts.app>
    @section('title', 'Edit Pegawai')

    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        <x-page-title>Edit Pegawai</x-page-title>
        <a href="{{ route('users.index') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 bg-white border border-gray-300 px-4 py-2 rounded-lg transition">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="max-w-xl bg-white rounded-xl shadow-sm p-6">

        @if($errors->any())
            <div class="mb-4 px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded text-sm">
                <strong>Terdapat kesalahan:</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('users.update', $user->id) }}" class="space-y-4">
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
                <label class="block text-sm font-medium text-gray-700 mb-1">NIP / Employee ID</label>
                <input type="text" name="employee_id" value="{{ old('employee_id', $user->employee_id) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">No HP</label>
                <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Unit Kerja</label>
                <select name="work_unit_id"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                    <option value="">-- Pilih Unit Kerja --</option>
                    @foreach ($workUnits as $wu)
                        <option value="{{ $wu->id }}"
                            {{ old('work_unit_id', $user->work_unit_id) == $wu->id ? 'selected' : '' }}>
                            {{ $wu->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Profesi</label>
                <select name="profession_id"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                    <option value="">-- Pilih Profesi --</option>
                    @foreach ($professions as $prof)
                        <option value="{{ $prof->id }}"
                            {{ old('profession_id', $user->profession_id) == $prof->id ? 'selected' : '' }}>
                            {{ $prof->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Posisi / Jabatan</label>
                <select name="position_id"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                    <option value="">-- Pilih Posisi --</option>
                    @foreach ($positions as $pos)
                        <option value="{{ $pos->id }}"
                            {{ old('position_id', $user->position_id) == $pos->id ? 'selected' : '' }}>
                            {{ $pos->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Tenaga</label>
                <select name="employment_type_id"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                    <option value="">-- Pilih Jenis Tenaga --</option>
                    @foreach ($employmentTypes as $et)
                        <option value="{{ $et->id }}"
                            {{ old('employment_type_id', $user->employment_type_id) == $et->id ? 'selected' : '' }}>
                            {{ $et->name }}</option>
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
                <a href="{{ route('users.index') }}"
                    class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-5 py-2 rounded-lg text-sm transition">
                    Batal
                </a>
            </div>
        </form>
    </div>

</x-layouts.app>
