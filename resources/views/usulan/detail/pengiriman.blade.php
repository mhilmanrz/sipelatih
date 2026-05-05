@php
    $currentStatus = $kegiatan->latestStatus ? $kegiatan->latestStatus->status : 'draft';

    $canSubmit = false;
    if ($kegiatan->start_date) {
        $submitWindow = \Carbon\Carbon::parse($kegiatan->start_date)->subDays(40)->startOfDay();
        $canSubmit = now()->startOfDay()->greaterThanOrEqualTo($submitWindow);
    }

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

<section class="mb-6">

    <!-- CARD: Aksi Pengiriman -->
    <div class="mb-6 text-center flex flex-col items-center justify-center min-h-[400px]">
        <div class="text-5xl mb-2 {{ $currentStatus === 'draft' || $currentStatus === 'revision' ? 'text-teal-500' : 'text-gray-400' }}">
            ✈
        </div>

        

        <div class="mb-4">
            <span class="inline-block px-4 py-1 rounded-full text-sm font-semibold {{ $statusColor }}">
                {{ mb_strtoupper($statusLabel) }}
            </span>
        </div>

        @if($currentStatus === 'draft' || $currentStatus === 'revision')
            @if(! $kegiatan->start_date)
                <p class="text-gray-500 mb-8 max-w-[80%] leading-relaxed">
                    Silakan lengkapi <strong class="text-gray-800">Tgl Mulai</strong> pada Data Kegiatan terlebih dahulu sebelum mengirim usulan.
                </p>
            @elseif(! $canSubmit)
                <p class="text-gray-500 mb-8 max-w-[80%] leading-relaxed">
                    Pengiriman usulan hanya dapat dilakukan maksimal <strong class="text-gray-800">40 hari</strong> sebelum tanggal mulai
                    (<strong>{{ \Carbon\Carbon::parse($kegiatan->start_date)->format('d M Y') }}</strong>).
                    Anda dapat mengirim mulai tanggal <strong>{{ \Carbon\Carbon::parse($kegiatan->start_date)->subDays(40)->format('d M Y') }}</strong>.
                </p>
            @else
                <p class="text-gray-500 mb-8 max-w-[80%] leading-relaxed">
                    Sebelum menekan tombol Kirim, pastikan Anda telah memeriksa ulang kelengkapan Data Kegiatan, KAK, Materi, Sasaran Profesi, Narasumber, dan Peserta dengan saksama.
                </p>
            @endif

            <button onclick="document.getElementById('modalKirim').style.display='flex';"
                class="{{ $canSubmit ? 'bg-[#007a7a] hover:bg-[#005f5f] cursor-pointer' : 'bg-gray-400 cursor-not-allowed' }} text-white font-semibold px-4 py-2 rounded-lg text-sm transition border-0"
                {{ ! $canSubmit ? 'disabled' : '' }}>
                🚀 Kirim Usulan
            </button>
        @elseif($currentStatus === 'submitted')
            <p class="text-gray-500 mb-8 max-w-[80%] leading-relaxed">
                Usulan Anda berstatus <strong class="text-gray-800">Menunggu Verifikasi</strong> dan sedang dalam antrean Tim Peninjau. Apabila Anda menyadari ada kesalahan, Anda masih bisa menarik kembali usulan ini selama belum direview.
            </p>

            <button onclick="document.getElementById('modalBatalKirim').style.display='flex';"
                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer border-0">
                ⮌ Menarik Kembali Usulan
            </button>
        @else
            <p class="text-gray-500 mb-8 max-w-[80%] leading-relaxed">
                Usulan ini sudah ditinjau / disetujui. Silakan cek menu <strong class="text-gray-800">Penilaian</strong> untuk melihat rekam jejak persetujuan.
            </p>
        @endif
    </div>

</section>

<!-- MODAL KIRIM -->
<div id="modalKirim"
    class="hidden fixed inset-0 w-full h-full bg-black/50 z-50 items-center justify-center">
    <div class="bg-white rounded-lg p-8 w-[400px] max-w-[90%] text-center shadow-2xl">

        <div class="text-5xl text-teal-500 mb-2">
            🚀
        </div>

        <h2 class="text-2xl font-bold mb-4 text-gray-900">Kirim Usulan?</h2>

        <p class="text-gray-600 mb-8 leading-normal">
            Data usulan akan diteruskan ke Tim Peninjau. Pastikan semua persyaratan, berkas, dan nama peserta telah lengkap.
        </p>

        <form action="{{ route('kegiatan.submit', $kegiatan->id) }}" method="POST">
            @csrf
            
            <div class="text-left mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Tambahan (Opsional)</label>
                <textarea name="note" rows="2" class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-teal-500 focus:outline-none" placeholder="Misal: 'Mohon direview urgensinya karena minggu depan acara dimulai...'"></textarea>
            </div>

            <div class="flex justify-between gap-4">
                <button type="button" onclick="document.getElementById('modalKirim').style.display='none';"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-4 py-2 rounded-lg text-sm transition flex-1 cursor-pointer border-0">
                    BATAL
                </button>
                <button type="submit"
                    class="bg-[#007a7a] hover:bg-[#005f5f] text-white font-semibold px-4 py-2 rounded-lg text-sm transition flex-1 cursor-pointer border-0">
                    YA, KIRIM
                </button>
            </div>
        </form>

    </div>
</div>

<!-- MODAL BATAL KIRIM -->
<div id="modalBatalKirim"
    class="hidden fixed inset-0 w-full h-full bg-black/50 z-50 items-center justify-center">
    <div class="bg-white rounded-lg p-8 w-[400px] max-w-[90%] text-center shadow-2xl">

        <div class="text-5xl text-red-500 mb-2">
            ⚠
        </div>

        <h2 class="text-xl font-bold mb-4 text-gray-900">Tarik Usulan?</h2>

        <p class="text-gray-600 mb-8">
            Anda yakin ingin menarik kembali usulan ini menjadi DRAFT? Hal ini biasanya dilakukan jika ada berkas penting yang terlewat untuk dikoreksi kembali.
        </p>

        <form action="{{ route('kegiatan.cancel_submit', $kegiatan->id) }}" method="POST">
            @csrf
            <div class="flex justify-between gap-4">
                <button type="button" onclick="document.getElementById('modalBatalKirim').style.display='none';"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-4 py-2 rounded-lg text-sm transition flex-1 cursor-pointer border-0">
                    TUTUP
                </button>
                <button type="submit"
                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition flex-1 cursor-pointer border-0">
                    BATALKAN PENGIRIMAN
                </button>
            </div>
        </form>

    </div>
</div>
