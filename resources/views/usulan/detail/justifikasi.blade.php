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

    <!-- Info Kegiatan: Tujuan & Justifikasi -->
    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 2rem; margin-bottom: 2rem;">
        <h3 style="font-size: 1rem; font-weight: 700; color: #374151; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.05em;">Tujuan & Justifikasi Kegiatan</h3>
        <div style="display: grid; grid-template-columns: 180px 1fr; gap: 0.75rem 0; font-size: 0.95rem; color: #374151;">
            <div style="font-weight: 600; color: #4b5563;">Tujuan</div>
            <div>: {{ $kegiatan->tujuan ?? '-' }}</div>
            <div style="font-weight: 600; color: #4b5563;">Justifikasi</div>
            <div>: {{ $kegiatan->justifikasi ?? '-' }}</div>
            <div style="font-weight: 600; color: #4b5563;">Target Kompetensi</div>
            <div>: {{ $kegiatan->target_kompetensi ?? '-' }}</div>
        </div>
    </div>

    <!-- TABLE TARGET -->
    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="font-size: 1rem; font-weight: 700; color: #374151; margin: 0; text-transform: uppercase; letter-spacing: 0.05em;">Target Kegiatan</h3>
            @php $existingNumbers = $kegiatan->activityTargets->pluck('target_number')->toArray(); @endphp
            @if (count($existingNumbers) < 3)
                <button id="openAddModal"
                    style="background-color: #14b8a6; color: white; font-weight: 600; padding: 0.5rem 1rem; border-radius: 6px; border: none; cursor: pointer;">
                    <svg class="w-5 h-5 mr-2 inline-block -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Target
                </button>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse border border-gray-200">
                <thead class="bg-[#007a7a] border border-white py-3 px-4 font-semibold">
                    <tr>
                        <th class="-300 text-center w-28 border border-white py-3 px-4 font-semibold">Target</th>
                        <th class="-300 text-left border border-white py-3 px-4 font-semibold">Deskripsi</th>
                        <th class="-300 text-center w-36 border border-white py-3 px-4 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse ($kegiatan->activityTargets as $target)
                        <tr>
                            <td class="-300 text-center font-semibold border border-gray-200 py-3 px-4">
                                Target {{ $target->target_number }}
                            </td>
                            <td class="-300 border border-gray-200 py-3 px-4">{{ $target->description }}</td>
                            <td class="-300 text-center border border-gray-200 py-3 px-4">
                                <button onclick="openEditModal({{ $target->id }}, {{ $target->target_number }}, {{ json_encode($target->description) }})"
                                    style="background-color: #3b82f6; color: white; padding: 0.25rem 0.75rem; border-radius: 4px; border: none; cursor: pointer; font-size: 0.75rem; margin-right: 4px;">
                                    EDIT
                                </button>
                                <form action="{{ route('kegiatan.target.destroy', ['kegiatan' => $kegiatan->id, 'id' => $target->id]) }}"
                                    method="POST" class="inline-block"
                                    onsubmit="return confirm('Hapus Target {{ $target->target_number }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        style="background-color: #ef4444; color: white; padding: 0.25rem 0.75rem; border-radius: 4px; border: none; cursor: pointer; font-size: 0.75rem;">
                                        HAPUS
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="-300 text-center text-gray-500 border border-gray-200 py-3 px-4">
                                Belum ada target yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- MODAL TAMBAH TARGET -->
<div id="addModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:50; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:8px; padding:2rem; width:480px; max-width:90%;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
            <h2 style="font-size:1.125rem; font-weight:700; margin:0;">Tambah Target</h2>
            <button onclick="closeAddModal()" style="background:none; border:none; font-size:1.5rem; cursor:pointer; color:#6b7280;">✖</button>
        </div>
        <form action="{{ route('kegiatan.target.store', $kegiatan->id) }}" method="POST">
            @csrf
            <div style="margin-bottom:1rem;">
                <label style="display:block; font-size:0.875rem; font-weight:600; margin-bottom:0.5rem; color:#374151;">Nomor Target</label>
                <select name="target_number" required style="width:100%; border:1px solid #d1d5db; border-radius:6px; padding:0.5rem 0.75rem; font-size:0.875rem;">
                    @foreach ([1, 2, 3] as $num)
                        @if (!in_array($num, $existingNumbers))
                            <option value="{{ $num }}">Target {{ $num }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom:1.5rem;">
                <label style="display:block; font-size:0.875rem; font-weight:600; margin-bottom:0.5rem; color:#374151;">Deskripsi</label>
                <textarea name="description" rows="4" required
                    style="width:100%; border:1px solid #d1d5db; border-radius:6px; padding:0.5rem 0.75rem; font-size:0.875rem; resize:vertical;"
                    placeholder="Tuliskan deskripsi target...">{{ old('description') }}</textarea>
            </div>
            <div style="display:flex; justify-content:flex-end; gap:0.5rem;">
                <button type="button" onclick="closeAddModal()"
                    style="background:#d1d5db; color:#1f2937; font-weight:600; padding:0.5rem 1rem; border-radius:6px; border:none; cursor:pointer;">
                    Batal
                </button>
                <button type="submit"
                    style="background:#0d9488; color:white; font-weight:600; padding:0.5rem 1rem; border-radius:6px; border:none; cursor:pointer;">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT TARGET -->
<div id="editModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:50; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:8px; padding:2rem; width:480px; max-width:90%;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
            <h2 id="editModalTitle" style="font-size:1.125rem; font-weight:700; margin:0;">Edit Target</h2>
            <button onclick="closeEditModal()" style="background:none; border:none; font-size:1.5rem; cursor:pointer; color:#6b7280;">✖</button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div style="margin-bottom:1.5rem;">
                <label style="display:block; font-size:0.875rem; font-weight:600; margin-bottom:0.5rem; color:#374151;">Deskripsi</label>
                <textarea id="editDescription" name="description" rows="4" required
                    style="width:100%; border:1px solid #d1d5db; border-radius:6px; padding:0.5rem 0.75rem; font-size:0.875rem; resize:vertical;"></textarea>
            </div>
            <div style="display:flex; justify-content:flex-end; gap:0.5rem;">
                <button type="button" onclick="closeEditModal()"
                    style="background:#d1d5db; color:#1f2937; font-weight:600; padding:0.5rem 1rem; border-radius:6px; border:none; cursor:pointer;">
                    Batal
                </button>
                <button type="submit"
                    style="background:#0d9488; color:white; font-weight:600; padding:0.5rem 1rem; border-radius:6px; border:none; cursor:pointer;">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('addModal').style.display = 'flex';
    }
    function closeAddModal() {
        document.getElementById('addModal').style.display = 'none';
    }
    function openEditModal(id, number, description) {
        document.getElementById('editModalTitle').textContent = 'Edit Target ' + number;
        document.getElementById('editDescription').value = description;
        document.getElementById('editForm').action = '/kegiatan/{{ $kegiatan->id }}/target/' + id;
        document.getElementById('editModal').style.display = 'flex';
    }
    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    document.getElementById('openAddModal')?.addEventListener('click', openAddModal);
</script>
