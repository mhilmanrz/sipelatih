<section>

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

    {{-- NARASUMBER SECTION --}}
    <x-detail-section title="Narasumber / Pembicara" icon="fa-user-tie">
        <form action="{{ route('kegiatan.narasumber.store', $kegiatan->id) }}" method="POST">
            @csrf
            <div class="grid grid-cols-[1fr_1fr_auto] gap-4 items-end mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">*Nama SDM</label>
                    <select name="user_id" required class="w-full border border-gray-300 rounded-md p-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">- PILIH NARASUMBER -</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">*Materi Ajar</label>
                    <select name="activity_material_id" required class="w-full border border-gray-300 rounded-md p-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">- PILIH MATERI -</option>
                        @foreach ($kegiatan->activityMaterials as $material)
                            <option value="{{ $material->id }}">{{ $material->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="bg-[#007a7a] hover:bg-[#005f5f] text-white font-semibold px-4 py-2 rounded-lg text-sm transition">
                    SIMPAN
                </button>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse border border-gray-200">
                <thead>
                    <tr>
                        <th class="bg-[#007a7a] text-white py-3 px-4 font-semibold text-sm text-center w-16">NO.</th>
                        <th class="bg-[#007a7a] text-white py-3 px-4 font-semibold text-sm text-left">Nama Narasumber</th>
                        <th class="bg-[#007a7a] text-white py-3 px-4 font-semibold text-sm text-left">Materi</th>
                        <th class="bg-[#007a7a] text-white py-3 px-4 font-semibold text-sm text-center w-44">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse ($allSpeakers as $index => $speaker)
                        <tr>
                            <td class="text-center border border-gray-200 py-3 px-4">{{ $index + 1 }}</td>
                            <td class="border border-gray-200 py-3 px-4">{{ $speaker->name }}</td>
                            <td class="border border-gray-200 py-3 px-4">{{ $speaker->material_name }}</td>
                            <td class="text-center border border-gray-200 py-3 px-4">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('kegiatan.narasumber.pdf', ['kegiatan' => $kegiatan->id, 'speaker' => $speaker->id]) }}" target="_blank" class="bg-[#007a7a] hover:bg-[#005f5f] text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition inline-block">PDF</a>
                                    <a href="{{ route('kegiatan.narasumber.docx', ['kegiatan' => $kegiatan->id, 'speaker' => $speaker->id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition inline-block">DOCX</a>
                                    <form action="{{ route('kegiatan.narasumber.destroy', ['kegiatan' => $kegiatan->id, 'id' => $speaker->id]) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus narasumber ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-gray-500 text-sm border border-gray-200 py-3 px-4">Belum ada data narasumber. Pastikan Anda mendaftarkan materi ajar terlebih dahulu di tab Materi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-detail-section>

    {{-- MODERATOR SECTION --}}
    <x-detail-section title="Moderator (Jika Ada)" icon="fa-comments">
        <form action="{{ route('kegiatan.moderator.store', $kegiatan->id) }}" method="POST">
            @csrf
            <div class="grid grid-cols-[1fr_1fr_auto] gap-4 items-end mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">*Nama Moderator</label>
                    <select name="user_id" required class="w-full border border-gray-300 rounded-md p-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">- PILIH MODERATOR -</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">*Materi Ajar</label>
                    <select name="activity_material_id" required class="w-full border border-gray-300 rounded-md p-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">- PILIH MATERI -</option>
                        @foreach ($kegiatan->activityMaterials as $material)
                            <option value="{{ $material->id }}">{{ $material->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="bg-[#007a7a] hover:bg-[#005f5f] text-white font-semibold px-4 py-2 rounded-lg text-sm transition">
                    SIMPAN
                </button>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse border border-gray-200">
                <thead>
                    <tr>
                        <th class="bg-[#007a7a] text-white py-3 px-4 font-semibold text-sm text-center w-16">NO.</th>
                        <th class="bg-[#007a7a] text-white py-3 px-4 font-semibold text-sm text-left">NIP</th>
                        <th class="bg-[#007a7a] text-white py-3 px-4 font-semibold text-sm text-left">Nama Moderator</th>
                        <th class="bg-[#007a7a] text-white py-3 px-4 font-semibold text-sm text-left">Materi</th>
                        <th class="bg-[#007a7a] text-white py-3 px-4 font-semibold text-sm text-left">Unit Kerja</th>
                        <th class="bg-[#007a7a] text-white py-3 px-4 font-semibold text-sm text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse ($allModerators as $index => $moderator)
                        <tr>
                            <td class="text-center border border-gray-200 py-3 px-4">{{ $index + 1 }}</td>
                            <td class="border border-gray-200 py-3 px-4">{{ $moderator->nip }}</td>
                            <td class="border border-gray-200 py-3 px-4">{{ $moderator->name }}</td>
                            <td class="border border-gray-200 py-3 px-4">{{ $moderator->material_name }}</td>
                            <td class="border border-gray-200 py-3 px-4">{{ $moderator->unit_kerja }}</td>
                            <td class="text-center border border-gray-200 py-3 px-4">
                                <form action="{{ route('kegiatan.moderator.destroy', ['kegiatan' => $kegiatan->id, 'id' => $moderator->id]) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus moderator ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-500 text-sm border border-gray-200 py-3 px-4">Belum ada data moderator.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-detail-section>
</section>