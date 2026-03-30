@php
    $currentStatus = $kegiatan->latestStatus ? $kegiatan->latestStatus->status : 'draft';
    
    // Warna untuk badge status
    $statusColor = match($currentStatus) {
        'draft' => 'bg-gray-200 text-gray-800',
        'submitted' => 'bg-blue-100 text-blue-800 border border-blue-300',
        'revision' => 'bg-yellow-100 text-yellow-800 border border-yellow-300',
        'accepted' => 'bg-green-100 text-green-800 border border-green-300',
        default => 'bg-gray-100 text-gray-800',
    };
    
    // Label terjemahan
    $statusLabel = match($currentStatus) {
        'draft' => 'Draft',
        'submitted' => 'Terkirim (Menunggu Verifikasi)',
        'revision' => 'Perlu Revisi',
        'accepted' => 'Diterima / Disetujui',
        default => 'Draft',
    };
@endphp

<section style="margin-top: 2rem;">

    <div class="max-w-3xl mx-auto">
        
        <!-- CARD: Aksi Pengiriman -->
        <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 3rem; text-align: center; display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 400px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
            <div style="font-size: 3rem; margin-bottom: 0.5rem; color: {{ $currentStatus === 'draft' || $currentStatus === 'revision' ? '#14b8a6' : '#9ca3af' }}">
                ✈
            </div>

            <h3 style="font-size: 1.5rem; font-weight: 700; color: #1f2937; margin-bottom: 1rem;">
                Status Usulan Resmi: 
                <span class="inline-block px-4 py-1 rounded-full text-sm font-semibold {{ $statusColor }} ml-2 align-middle">
                    {{ mb_strtoupper($statusLabel) }}
                </span>
            </h3>

            @if($currentStatus === 'draft' || $currentStatus === 'revision')
                <p style="color: #6b7280; margin-bottom: 2rem; max-width: 80%; line-height: 1.6;">
                    Sebelum menekan tombol Kirim, pastikan Anda telah memeriksa ulang kelengkapan Data Kegiatan, KAK, Materi, Sasaran Profesi, Narasumber, dan Peserta dengan saksama.
                </p>

                <button onclick="document.getElementById('modalKirim').style.display='flex';"
                    class="font-semibold py-3 px-8 text-lg rounded-full shadow-lg transition-colors"
                    style="cursor: pointer; background-color: #0d9488; color: white; border: none;">
                    🚀 Kirim Usulan
                </button>
            @elseif($currentStatus === 'submitted')
                <p style="color: #6b7280; margin-bottom: 2rem; max-width: 80%; line-height: 1.6;">
                    Usulan Anda berstatus <strong class="text-gray-800">Menunggu Verifikasi</strong> dan sedang dalam antrean Tim Peninjau. Apabila Anda menyadari ada kesalahan, Anda masih bisa menarik kembali usulan ini selama belum direview.
                </p>

                <button onclick="document.getElementById('modalBatalKirim').style.display='flex';"
                    class="font-semibold py-3 px-8 text-lg rounded-full shadow transition-colors"
                    style="cursor: pointer; background-color: #fef2f2; color: #dc2626; border: 1px solid #fecaca;">
                    ⮌ Menarik Kembali Usulan
                </button>
            @else
                <p style="color: #6b7280; margin-bottom: 2rem; max-width: 80%; line-height: 1.6;">
                    Usulan ini sudah ditinjau / disetujui. Silakan cek menu <strong class="text-gray-800">Penilaian</strong> untuk melihat rekam jejak persetujuan.
                </p>
            @endif
        </div>
        
    </div>
</section>

<!-- MODAL KIRIM -->
<div id="modalKirim"
    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 50; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 8px; padding: 2rem; width: 400px; max-width: 90%; text-align: center; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);">

        <div style="font-size: 3rem; color: #14b8a6; margin-bottom: 0.5rem;">
            🚀
        </div>

        <h2 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 1rem; color: #111827;">Kirim Usulan?</h2>

        <p style="color: #4b5563; margin-bottom: 2rem; line-height: 1.5;">
            Data usulan akan diteruskan ke Tim Peninjau. Pastikan semua persyaratan, berkas, dan nama peserta telah lengkap.
        </p>

        <form action="{{ route('kegiatan.submit', $kegiatan->id) }}" method="POST">
            @csrf
            
            <div style="text-align: left; margin-bottom: 1.5rem;">
                <label class="block text-gray-700 text-sm font-bold mb-2">Catatan Tambahan (Opsional)</label>
                <textarea name="note" rows="2" class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-teal-500 focus:outline-none" placeholder="Misal: 'Mohon direview urgensinya karena minggu depan acara dimulai...'"></textarea>
            </div>

            <div style="display: flex; justify-content: space-between; gap: 1rem;">
                <button type="button" onclick="document.getElementById('modalKirim').style.display='none';"
                    class="font-bold py-3 px-6 rounded-lg transition-colors flex-1"
                    style="cursor: pointer; border: none; background-color: #f3f4f6; color: #374151;">
                    BATAL
                </button>
                <button type="submit"
                    class="font-bold py-3 px-6 rounded-lg shadow transition-colors flex-1"
                    style="cursor: pointer; border: none; background-color: #0d9488; color: white;">
                    YA, KIRIM
                </button>
            </div>
        </form>

    </div>
</div>

<!-- MODAL BATAL KIRIM -->
<div id="modalBatalKirim"
    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 50; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 8px; padding: 2rem; width: 400px; max-width: 90%; text-align: center; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);">

        <div style="font-size: 3rem; color: #ef4444; margin-bottom: 0.5rem;">
            ⚠
        </div>

        <h2 style="font-size: 1.25rem; font-weight: bold; margin-bottom: 1rem; color: #111827;">Tarik Usulan?</h2>

        <p style="color: #4b5563; margin-bottom: 2rem;">
            Anda yakin ingin menarik kembali usulan ini menjadi DRAFT? Hal ini biasanya dilakukan jika ada berkas penting yang terlewat untuk dikoreksi kembali.
        </p>

        <form action="{{ route('kegiatan.cancel_submit', $kegiatan->id) }}" method="POST">
            @csrf
            <div style="display: flex; justify-content: space-between; gap: 1rem;">
                <button type="button" onclick="document.getElementById('modalBatalKirim').style.display='none';"
                    class="font-bold py-3 px-6 rounded-lg transition-colors flex-1"
                    style="cursor: pointer; border: none; background-color: #f3f4f6; color: #374151;">
                    TUTUP
                </button>
                <button type="submit"
                    class="font-bold py-3 px-6 rounded-lg shadow transition-colors flex-1"
                    style="cursor: pointer; border: none; background-color: #ef4444; color: white;">
                    BATALKAN PENGIRIMAN
                </button>
            </div>
        </form>

    </div>
</div>