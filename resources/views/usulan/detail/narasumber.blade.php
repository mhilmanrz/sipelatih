<section style="margin-top: 2rem;">

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

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

    @php
        $allSpeakers = collect();
        $allModerators = collect();

        if ($kegiatan->activityMaterials) {
            foreach ($kegiatan->activityMaterials as $material) {
                if ($material->speakers) {
                    foreach ($material->speakers as $speaker) {
                        $allSpeakers->push((object)[
                            'id' => $speaker->id,
                            'name' => $speaker->user->name ?? '-',
                            'material_name' => $material->name,
                        ]);
                    }
                }

                if ($material->moderators) {
                    foreach ($material->moderators as $moderator) {
                        $allModerators->push((object)[
                            'id' => $moderator->id,
                            'nip' => $moderator->user->nip ?? '-',
                            'name' => $moderator->user->name ?? '-',
                            'material_name' => $material->name,
                            'unit_kerja' => $moderator->user->workUnit->name ?? '-',
                        ]);
                    }
                }
            }
        }
    @endphp

    <!-- CARD -->
    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 2rem;">

        <!-- NARASUMBER SECTION -->
        <div style="font-weight: bold; font-size: 1.125rem; margin-bottom: 1rem; color: #111827; border-bottom: 1px solid #e5e7eb; padding-bottom: 0.5rem;">
            Narasumber / Pembicara
        </div>

        <!-- FORM NARASUMBER -->
        <form action="{{ route('kegiatan.narasumber.store', $kegiatan->id) }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 1rem; align-items: end; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">*Nama SDM</label>
                    <select name="user_id" required class="w-full border border-gray-300 rounded-md p-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">- PILIH NARASUMBER -</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">*Materi Ajar</label>
                    <select name="activity_material_id" required class="w-full border border-gray-300 rounded-md p-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">- PILIH MATERI -</option>
                        @foreach ($kegiatan->activityMaterials as $material)
                            <option value="{{ $material->id }}">{{ $material->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="hover:bg-teal-600 text-white font-semibold py-2 px-4 rounded shadow transition-colors h-[42px]" style="background-color: #14b8a6; color: white; cursor: pointer;">
                    💾 SIMPAN
                </button>
            </div>
        </form>

        <!-- TABLE NARASUMBER -->
        <div class="overflow-x-auto mb-8">
            <table class="w-full text-sm border-collapse border border-gray-200">
                <thead class="bg-[#007a7a] border border-white py-3 px-4 font-semibold">
                    <tr>
                        <th class="-300 text-center w-16 border border-white py-3 px-4 font-semibold">NO.</th>
                        <th class="-300 text-left border border-white py-3 px-4 font-semibold">Nama Narasumber</th>
                        <th class="-300 text-left border border-white py-3 px-4 font-semibold">Materi</th>
                        <th class="-300 text-center w-32 border border-white py-3 px-4 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse ($allSpeakers as $index => $speaker)
                        <tr>
                            <td class="-300 text-center border border-gray-200 py-3 px-4">{{ $index + 1 }}</td>
                            <td class="-300 border border-gray-200 py-3 px-4">{{ $speaker->name }}</td>
                            <td class="-300 border border-gray-200 py-3 px-4">{{ $speaker->material_name }}</td>
                            <td class="-300 text-center border border-gray-200 py-3 px-4">
                                <form action="{{ route('kegiatan.narasumber.destroy', ['kegiatan' => $kegiatan->id, 'id' => $speaker->id]) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus narasumber ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"   style="background-color: #ef4444;" class="text-white px-3 py-1.5 rounded hover:bg-[#dc2626] text-sm font-semibold transition inline-block">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="-300 text-center text-gray-500 text-sm border border-gray-200 py-3 px-4">Belum ada data narasumber. Pastikan Anda mendaftarkan materi ajar terlebih dahulu di tab Materi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- MODERATOR SECTION -->
        <div style="font-weight: bold; font-size: 1.125rem; margin-bottom: 1rem; color: #111827; border-bottom: 1px solid #e5e7eb; padding-bottom: 0.5rem;">
            Moderator (Jika Ada)
        </div>

        <form action="{{ route('kegiatan.moderator.store', $kegiatan->id) }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 1rem; align-items: end; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">*Nama Moderator</label>
                    <select name="user_id" required class="w-full border border-gray-300 rounded-md p-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">- PILIH MODERATOR -</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">*Materi Ajar</label>
                    <select name="activity_material_id" required class="w-full border border-gray-300 rounded-md p-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">- PILIH MATERI -</option>
                        @foreach ($kegiatan->activityMaterials as $material)
                            <option value="{{ $material->id }}">{{ $material->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="hover:bg-teal-600 text-white font-semibold py-2 px-4 rounded shadow transition-colors h-[42px]" style="background-color: #14b8a6; color: white; cursor: pointer;">
                    💾 SIMPAN
                </button>
            </div>
        </form>

        <!-- TABLE MODERATOR -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse border border-gray-200">
                <thead class="bg-[#007a7a] border border-white py-3 px-4 font-semibold">
                    <tr>
                        <th class="-300 text-center w-16 border border-white py-3 px-4 font-semibold">NO.</th>
                        <th class="-300 text-left border border-white py-3 px-4 font-semibold">NIP</th>
                        <th class="-300 text-left border border-white py-3 px-4 font-semibold">Nama Moderator</th>
                        <th class="-300 text-left border border-white py-3 px-4 font-semibold">Materi</th>
                        <th class="-300 text-left border border-white py-3 px-4 font-semibold">Unit Kerja</th>
                        <th class="-300 text-center w-32 border border-white py-3 px-4 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse ($allModerators as $index => $moderator)
                        <tr>
                            <td class="-300 text-center border border-gray-200 py-3 px-4">{{ $index + 1 }}</td>
                            <td class="-300 border border-gray-200 py-3 px-4">{{ $moderator->nip }}</td>
                            <td class="-300 border border-gray-200 py-3 px-4">{{ $moderator->name }}</td>
                            <td class="-300 border border-gray-200 py-3 px-4">{{ $moderator->material_name }}</td>
                            <td class="-300 border border-gray-200 py-3 px-4">{{ $moderator->unit_kerja }}</td>
                            <td class="-300 text-center border border-gray-200 py-3 px-4">
                                <form action="{{ route('kegiatan.moderator.destroy', ['kegiatan' => $kegiatan->id, 'id' => $moderator->id]) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus moderator ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"   style="background-color: #ef4444;" class="text-white px-3 py-1.5 rounded hover:bg-[#dc2626] text-sm font-semibold transition inline-block">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="-300 text-center text-gray-500 text-sm border border-gray-200 py-3 px-4">Belum ada data moderator.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</section>