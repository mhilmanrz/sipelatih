<x-layouts.app>
    <x-slot:title>Tambah Role</x-slot>

@push('styles')
    <style>
        .tw-wrap p, .tw-wrap h1, .tw-wrap h2, .tw-wrap h3, .tw-wrap h4, .tw-wrap h5, .tw-wrap h6,
        .tw-wrap span, .tw-wrap div, .tw-wrap a, .tw-wrap button, .tw-wrap input, .tw-wrap label {
            font-family: inherit;
        }
    </style>
@endpush

    <div class="tw-wrap p-6 max-w-4xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('roles.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                <i class="fa-solid fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-2xl font-bold text-white">TAMBAH ROLE</h1>
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
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Role</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="mt-1 focus:ring-teal-500 focus:border-teal-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2 border"
                        placeholder="Contoh: Manajer, IT Support">
                </div>

                {{-- HAK AKSES (PERMISSIONS) SECTION --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3 border-b pb-2">Hak Akses (Permissions)</label>
                    
                    @php
                        $groupedPermissions = [
                            'Umum & Dashboard' => [],
                            'Evaluasi & Laporan' => [],
                            'Master Data (Pegawai & Otoritas)' => [],
                            'Kamus Kegiatan (Dictionaries)' => []
                        ];

                        foreach($permissions as $permission) {
                            $name = $permission->name;
                            if (str_contains($name, 'dashboard') || str_contains($name, 'usulan diklat') || str_contains($name, 'monitoring') || str_contains($name, 'settings')) {
                                $groupedPermissions['Umum & Dashboard'][] = $permission;
                            } elseif (str_contains($name, 'budget') || str_contains($name, 'pagu') || str_contains($name, 'laporan') || str_contains($name, 'evaluasi')) {
                                $groupedPermissions['Evaluasi & Laporan'][] = $permission;
                            } elseif (str_contains($name, 'users') || str_contains($name, 'accounts') || str_contains($name, 'roles') || str_contains($name, 'permissions') || str_contains($name, 'positions') || str_contains($name, 'ranks') || str_contains($name, 'work units') || str_contains($name, 'professions') || str_contains($name, 'employment')) {
                                $groupedPermissions['Master Data (Pegawai & Otoritas)'][] = $permission;
                            } else {
                                $groupedPermissions['Kamus Kegiatan (Dictionaries)'][] = $permission;
                            }
                        }
                    @endphp

                    <div class="space-y-6">
                        @foreach($groupedPermissions as $groupName => $perms)
                            @if(count($perms) > 0)
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <h3 class="text-xs font-bold text-teal-800 uppercase tracking-wider mb-3">{{ $groupName }}</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        @foreach($perms as $perm)
                                            <label class="relative flex items-start cursor-pointer hover:bg-white p-2 rounded transition border border-transparent hover:border-gray-200">
                                                <div class="flex items-center h-5">
                                                    <input type="checkbox" name="permissions[]" value="{{ $perm->id }}"
                                                        class="focus:ring-teal-500 h-4 w-4 text-teal-600 border-gray-300 rounded"
                                                        {{ (is_array(old('permissions')) && in_array($perm->id, old('permissions'))) ? 'checked' : '' }}>
                                                </div>
                                                <div class="ml-3 text-xs">
                                                    <span class="font-medium text-gray-700">{{ ucwords(str_replace('view ', '', $perm->name)) }}</span>
                                                    <p class="text-gray-500 font-mono text-[10px]">{{ $perm->name }}</p>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('roles.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded shadow hover:bg-gray-300">
                        Batal
                    </a>
                    <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded shadow hover:bg-teal-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
