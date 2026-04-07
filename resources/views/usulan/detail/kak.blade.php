<!-- KAK CONTENT CARD -->
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

    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 2rem;">

        <h3 style="font-size: 1.25rem; font-weight: 600; color: #374151; margin-bottom: 1.5rem;">
            Dokumen KAK (Kerangka Acuan Kerja)
        </h3>

        {{-- Tombol Aksi --}}
        <div style="display: flex; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 2rem;">
            <button onclick="document.getElementById('kakModal').style.display='flex'"
                style="background-color: #14b8a6; color: white; font-weight: 600; padding: 0.5rem 1.25rem; border: none; border-radius: 6px; cursor: pointer;">
                ⬆ Unggah KAK (PDF)
            </button>

            <a href="{{ route('kegiatan.kak.template') }}"
                style="background-color: #3b82f6; color: white; font-weight: 600; padding: 0.5rem 1.25rem; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center;">
                ⬇ Unduh Template KAK (.docx)
            </a>

            @php $kakFile = $kegiatan->kakFiles->first(); @endphp
            @if ($kakFile)
                <a href="{{ Storage::url($kakFile->url) }}" target="_blank"
                    style="background-color: #6b7280; color: white; font-weight: 600; padding: 0.5rem 1.25rem; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center;">
                    👁 Lihat KAK Terkini
                </a>
                <form action="{{ route('kegiatan.kak.destroy', [$kegiatan->id, $kakFile->id]) }}" method="POST"
                    onsubmit="return confirm('Hapus file KAK ini?')" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        style="background-color: #ef4444; color: white; font-weight: 600; padding: 0.5rem 1.25rem; border: none; border-radius: 6px; cursor: pointer;">
                        🗑 Hapus KAK
                    </button>
                </form>
            @endif
        </div>

        {{-- Preview PDF --}}
        @if ($kakFile)
            <div style="border: 1px solid #d1d5db; border-radius: 4px; overflow: hidden; height: 600px;">
                <iframe src="{{ Storage::url($kakFile->url) }}" width="100%" height="100%" style="border: none;"></iframe>
            </div>
            <p style="color: #6b7280; font-size: 0.8rem; margin-top: 0.5rem;">
                Diunggah: {{ $kakFile->created_at->format('d M Y, H:i') }} WIB
            </p>
        @else
            <div style="border: 2px dashed #d1d5db; padding: 4rem; text-align: center; border-radius: 8px; background: #f9fafb;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">📄</div>
                <p style="color: #6b7280; font-style: italic; margin: 0;">Belum ada dokumen KAK yang diunggah.</p>
                <p style="color: #9ca3af; font-size: 0.875rem; margin-top: 0.5rem;">
                    Unduh template di atas, isi, lalu unggah dalam format PDF.
                </p>
            </div>
        @endif

    </div>
</section>

<!-- MODAL UPLOAD KAK -->
<div id="kakModal"
    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 50; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 8px; padding: 2rem; width: 450px; max-width: 90%;">

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.25rem; font-weight: bold; margin: 0;">Unggah Dokumen KAK</h2>
            <button onclick="document.getElementById('kakModal').style.display='none'"
                style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #6b7280;">✖</button>
        </div>

        <form action="{{ route('kegiatan.kak.store', $kegiatan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">
                    Pilih File PDF *
                </label>
                <input type="file" name="kak_file" accept=".pdf" required
                    style="width: 100%; border: 1px solid #d1d5db; border-radius: 6px; padding: 0.5rem; font-size: 0.875rem;">
                <p style="color: #6b7280; font-size: 0.8rem; margin-top: 0.375rem;">Format: PDF, maksimal 10MB.</p>
                @if ($kakFile)
                    <p style="color: #ef4444; font-size: 0.8rem; margin-top: 0.375rem;">
                        ⚠ File KAK yang sudah ada akan digantikan.
                    </p>
                @endif
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
                <button type="button" onclick="document.getElementById('kakModal').style.display='none'"
                    style="background-color: #d1d5db; color: #1f2937; font-weight: 600; padding: 0.5rem 1.25rem; border: none; border-radius: 6px; cursor: pointer;">
                    Batal
                </button>
                <button type="submit"
                    style="background-color: #0d9488; color: white; font-weight: 600; padding: 0.5rem 1.25rem; border: none; border-radius: 6px; cursor: pointer;">
                    Simpan
                </button>
            </div>
        </form>

    </div>
</div>
