@php
    $stage = $kegiatan->currentStage();
    $currentStatus = $kegiatan->currentStatus();
    $role = auth()->user()->stageRole();
    $isSuperadmin = $role === 'superadmin';

    // 1. Kegiatan: Check if the key fields are filled
    $kegiatanRequiredFields = [
        'activity_name_id', 'activity_type_id', 'activity_category_id', 'activity_scope_id',
        'material_type_id', 'activity_method_id', 'batch_id', 'activity_format_id',
        'quota_participant', 'work_unit_id', 'pic_user_id', 'organizer_pic_id',
        'date', 'reference_number', 'start_date', 'end_date', 'start_time',
        'end_time', 'tempat', 'fund_source_id', 'budget_amount'
    ];
    $kegiatanComplete = true;
    foreach ($kegiatanRequiredFields as $field) {
        if (is_null($kegiatan->{$field}) || $kegiatan->{$field} === '') {
            $kegiatanComplete = false;
            break;
        }
    }

    // 2. Dokumen: (Auto-generated from Kegiatan details, so it's complete if Kegiatan is complete)
    $dokumenComplete = $kegiatanComplete;

    // 3. Justifikasi: (tujuan, justifikasi, and targets count > 0)
    $justifikasiComplete = !empty($kegiatan->tujuan)
        && !empty($kegiatan->justifikasi)
        && $kegiatan->activityTargets->isNotEmpty();

    // 4. Sasaran Profesi: (professions count > 0)
    $sasaranComplete = $kegiatan->activityProfessions->isNotEmpty();

    // 5. KAK: (kak files count > 0)
    $kakComplete = $kegiatan->activityKakFiles->isNotEmpty();

    // 6. Materi: (materials count > 0)
    $materiComplete = $kegiatan->activityMaterials->isNotEmpty();

    // 7. Narasumber: (speakers count > 0)
    $narasumberComplete = $kegiatan->speakers->isNotEmpty();

    // 8. Peserta: (participants count > 0)
    $pesertaComplete = $kegiatan->activityParticipants->isNotEmpty();

    // 9. Waktu Pengajuan: (diff in days < 45)
    $waktuComplete = false;
    $daysRemaining = null;
    if ($kegiatan->start_date) {
        $startDate = \Carbon\Carbon::parse($kegiatan->start_date)->startOfDay();
        $today = now()->startOfDay();
        $daysRemaining = $today->diffInDays($startDate, false);
        $waktuComplete = $daysRemaining < 45;
    }

    $allRequirementsMet = $kegiatanComplete
        && $dokumenComplete
        && $justifikasiComplete
        && $sasaranComplete
        && $kakComplete
        && $materiComplete
        && $narasumberComplete
        && $pesertaComplete
        && $waktuComplete;

    $stageLabels = [
        'pengusul' => 'Pengusul',
        'perencanaan' => 'Perencanaan',
        'penyelenggara' => 'Penyelenggara',
        'evaluasi' => 'Evaluasi',
    ];

    $statusColor = match(true) {
        $currentStatus === 'draft' => 'bg-gray-200 text-gray-800',
        $currentStatus === 'pending' => 'bg-blue-100 text-blue-800 border border-blue-300',
        $currentStatus === 'revision' => 'bg-yellow-100 text-yellow-800 border border-yellow-300',
        $currentStatus === 'completed' => 'bg-green-100 text-green-800 border border-green-300',
        default => 'bg-gray-100 text-gray-800',
    };

    $statusLabel = match(true) {
        $currentStatus === 'draft' => 'Draft',
        $currentStatus === 'pending' => 'Menunggu '.$stageLabels[$stage],
        $currentStatus === 'revision' => 'Revisi ('.$stageLabels[$stage].')',
        $currentStatus === 'completed' => 'Selesai',
        default => 'Draft',
    };

    $revisionNote = $currentStatus === 'revision' ? $kegiatan->latestStatus?->note : null;
@endphp

<section class="mb-6">

    <!-- CARD: Aksi Pengiriman -->
    <div class="mb-6 text-center flex flex-col items-center justify-center min-h-[400px]">
        <div class="text-5xl mb-2 {{ in_array($currentStatus, ['draft', 'revision']) ? 'text-teal-500' : 'text-gray-400' }}">
            ✈
        </div>

        <div class="mb-4">
            <span class="inline-block px-4 py-1 rounded-full text-sm font-semibold {{ $statusColor }}">
                {{ mb_strtoupper($statusLabel) }}
            </span>
        </div>

        @if($revisionNote)
            <div class="mb-6 w-full max-w-2xl bg-yellow-50 border-l-4 border-yellow-400 p-4 text-left rounded">
                <p class="text-sm text-yellow-800"><strong>Catatan Revisi:</strong> {{ $revisionNote }}</p>
            </div>
        @endif

        @if($stage === 'pengusul' && in_array($currentStatus, ['draft', 'revision']))
            @if($role === 'pengusul' || $isSuperadmin)
                <div class="mb-8 w-full max-w-2xl bg-gray-50 rounded-xl border border-gray-200 p-6 text-left shadow-sm">
                    <h4 class="text-base font-bold text-gray-800 mb-5 flex items-center gap-2 border-b border-gray-200 pb-3">
                        <i class="fa fa-list-check text-[#007a7a]"></i>
                        Persyaratan Pengiriman Usulan
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Kegiatan -->
                        <div class="flex items-start gap-3">
                            @if($kegiatanComplete)
                                <i class="fa fa-circle-check text-green-500 mt-0.5 text-base"></i>
                                <div>
                                    <span class="text-sm font-semibold text-gray-800">Data Kegiatan</span>
                                    <p class="text-xs text-gray-500">Sudah terisi lengkap.</p>
                                </div>
                            @else
                                <i class="fa fa-circle-xmark text-red-400 mt-0.5 text-base"></i>
                                <div>
                                    <span class="text-sm font-semibold text-gray-700">Data Kegiatan</span>
                                    <p class="text-xs text-red-500">Belum diisi lengkap (periksa tgl, tempat, anggaran, dll).</p>
                                </div>
                            @endif
                        </div>

                        <!-- Dokumen -->
                        <div class="flex items-start gap-3">
                            @if($dokumenComplete)
                                <i class="fa fa-circle-check text-green-500 mt-0.5 text-base"></i>
                                <div>
                                    <span class="text-sm font-semibold text-gray-800">Dokumen</span>
                                    <p class="text-xs text-gray-500">Dokumen administrasi siap didownload.</p>
                                </div>
                            @else
                                <i class="fa fa-circle-xmark text-red-400 mt-0.5 text-base"></i>
                                <div>
                                    <span class="text-sm font-semibold text-gray-700">Dokumen</span>
                                    <p class="text-xs text-red-500">Dokumen belum siap.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Justifikasi -->
                        <div class="flex items-start gap-3">
                            @if($justifikasiComplete)
                                <i class="fa fa-circle-check text-green-500 mt-0.5 text-base"></i>
                                <div>
                                    <span class="text-sm font-semibold text-gray-800">Justifikasi & Target</span>
                                    <p class="text-xs text-gray-500">Tujuan, justifikasi, dan target telah diisi.</p>
                                </div>
                            @else
                                <i class="fa fa-circle-xmark text-red-400 mt-0.5 text-base"></i>
                                <div>
                                    <span class="text-sm font-semibold text-gray-700">Justifikasi & Target</span>
                                    <p class="text-xs text-red-500">Tujuan/justifikasi kosong atau belum ada target.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Sasaran Profesi -->
                        <div class="flex items-start gap-3">
                            @if($sasaranComplete)
                                <i class="fa fa-circle-check text-green-500 mt-0.5 text-base"></i>
                                <div>
                                    <span class="text-sm font-semibold text-gray-800">Sasaran Profesi</span>
                                    <p class="text-xs text-gray-500">Sasaran profesi telah diisi.</p>
                                </div>
                            @else
                                <i class="fa fa-circle-xmark text-red-400 mt-0.5 text-base"></i>
                                <div>
                                    <span class="text-sm font-semibold text-gray-700">Sasaran Profesi</span>
                                    <p class="text-xs text-red-500">Belum ada sasaran profesi yang ditambahkan.</p>
                                </div>
                            @endif
                        </div>

                        <!-- KAK -->
                        <div class="flex items-start gap-3">
                            @if($kakComplete)
                                <i class="fa fa-circle-check text-green-500 mt-0.5 text-base"></i>
                                <div>
                                    <span class="text-sm font-semibold text-gray-800">Dokumen KAK</span>
                                    <p class="text-xs text-gray-500">File KAK telah diunggah.</p>
                                </div>
                            @else
                                <i class="fa fa-circle-xmark text-red-400 mt-0.5 text-base"></i>
                                <div>
                                    <span class="text-sm font-semibold text-gray-700">Dokumen KAK</span>
                                    <p class="text-xs text-red-500">Silakan unggah dokumen KAK terlebih dahulu.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Materi -->
                        <div class="flex items-start gap-3">
                            @if($materiComplete)
                                <i class="fa fa-circle-check text-green-500 mt-0.5 text-base"></i>
                                <div>
                                    <span class="text-sm font-semibold text-gray-800">Materi Kegiatan</span>
                                    <p class="text-xs text-gray-500">Daftar materi telah diisi.</p>
                                </div>
                            @else
                                <i class="fa fa-circle-xmark text-red-400 mt-0.5 text-base"></i>
                                <div>
                                    <span class="text-sm font-semibold text-gray-700">Materi Kegiatan</span>
                                    <p class="text-xs text-red-500">Belum ada materi yang ditambahkan.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Narasumber -->
                        <div class="flex items-start gap-3">
                            @if($narasumberComplete)
                                <i class="fa fa-circle-check text-green-500 mt-0.5 text-base"></i>
                                <div>
                                    <span class="text-sm font-semibold text-gray-800">Narasumber</span>
                                    <p class="text-xs text-gray-500">Narasumber telah diisi.</p>
                                </div>
                            @else
                                <i class="fa fa-circle-xmark text-red-400 mt-0.5 text-base"></i>
                                <div>
                                    <span class="text-sm font-semibold text-gray-700">Narasumber</span>
                                    <p class="text-xs text-red-500">Belum ada narasumber yang ditambahkan.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Peserta -->
                        <div class="flex items-start gap-3">
                            @if($pesertaComplete)
                                <i class="fa fa-circle-check text-green-500 mt-0.5 text-base"></i>
                                <div>
                                    <span class="text-sm font-semibold text-gray-800">Peserta Kegiatan</span>
                                    <p class="text-xs text-gray-500">Peserta telah didaftarkan.</p>
                                </div>
                            @else
                                <i class="fa fa-circle-xmark text-red-400 mt-0.5 text-base"></i>
                                <div>
                                    <span class="text-sm font-semibold text-gray-700">Peserta Kegiatan</span>
                                    <p class="text-xs text-red-500">Belum ada peserta yang didaftarkan.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Waktu Pengajuan -->
                        <div class="flex items-start gap-3 md:col-span-2 border-t border-gray-200 pt-3 mt-1">
                            @if(!$kegiatan->start_date)
                                <i class="fa fa-circle-xmark text-red-400 mt-0.5 text-base"></i>
                                <div>
                                    <span class="text-sm font-semibold text-gray-700">Tanggal Pelaksanaan</span>
                                    <p class="text-xs text-red-500">Tanggal pelaksanaan belum diisi pada Data Kegiatan.</p>
                                </div>
                            @elseif($waktuComplete)
                                <i class="fa fa-circle-check text-green-500 mt-0.5 text-base"></i>
                                <div>
                                    <span class="text-sm font-semibold text-gray-800">Waktu Pengajuan</span>
                                    <p class="text-xs text-gray-500">Usulan diajukan kurang dari 45 hari kalender sebelum tanggal mulai ({{ $daysRemaining }} hari tersisa).</p>
                                </div>
                            @else
                                <i class="fa fa-circle-xmark text-red-400 mt-0.5 text-base"></i>
                                <div>
                                    <span class="text-sm font-semibold text-gray-700">Waktu Pengajuan</span>
                                    <p class="text-xs text-red-500">
                                        Pengiriman usulan hanya dapat dilakukan maksimal 45 hari sebelum tanggal mulai.
                                        Anda dapat mengirim mulai tanggal <strong>{{ \Carbon\Carbon::parse($kegiatan->start_date)->subDays(45)->format('d M Y') }}</strong> ({{ $daysRemaining }} hari tersisa).
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if($allRequirementsMet)
                    <p class="text-gray-500 mb-8 max-w-[80%] leading-relaxed">
                        Sebelum menekan tombol Kirim, pastikan Anda telah memeriksa ulang kelengkapan Data Kegiatan, KAK, Materi, Sasaran Profesi, Narasumber, dan Peserta dengan saksama.
                    </p>
                    <button onclick="document.getElementById('modalKirim').style.display='flex';"
                        class="bg-[#007a7a] hover:bg-[#005f5f] text-white font-semibold px-4 py-2 rounded-lg text-sm transition border-0 cursor-pointer">
                        🚀 Kirim Usulan
                    </button>
                @else
                    <p class="text-red-500 font-semibold mb-8 max-w-[80%] leading-relaxed">
                        Tombol kirim tidak muncul karena belum semua persyaratan terpenuhi. Silakan lengkapi checklist di atas.
                    </p>
                @endif
            @else
                <p class="text-gray-500 mb-8 max-w-[80%] leading-relaxed">
                    Menunggu Pengusul melengkapi dan mengirim usulan ini.
                </p>
            @endif

        @elseif($stage === 'perencanaan' && $currentStatus === 'pending')
            @if($role === 'perencanaan' || $isSuperadmin)
                <p class="text-gray-500 mb-8 max-w-[80%] leading-relaxed">
                    Usulan ini menunggu review Anda sebagai Tim Perencanaan.
                </p>
                <div class="flex gap-3">
                    <button onclick="document.getElementById('modalApprove').style.display='flex';"
                        class="bg-[#007a7a] hover:bg-[#005f5f] text-white font-semibold px-4 py-2 rounded-lg text-sm transition border-0 cursor-pointer">
                        ✔ Setujui
                    </button>
                    <button onclick="document.getElementById('modalReturnRevision').style.display='flex';"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-4 py-2 rounded-lg text-sm transition border-0 cursor-pointer">
                        ⮌ Kembalikan untuk Revisi
                    </button>
                </div>
            @elseif($role === 'pengusul')
                <p class="text-gray-500 mb-8 max-w-[80%] leading-relaxed">
                    Usulan Anda berstatus <strong class="text-gray-800">Menunggu Perencanaan</strong>. Apabila Anda menyadari ada kesalahan, Anda masih bisa menarik kembali usulan ini selama belum direview.
                </p>
                <button onclick="document.getElementById('modalBatalKirim').style.display='flex';"
                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer border-0">
                    ⮌ Menarik Kembali Usulan
                </button>
            @else
                <p class="text-gray-500 mb-8 max-w-[80%] leading-relaxed">
                    Usulan sedang ditinjau oleh Tim Perencanaan.
                </p>
            @endif

        @elseif($stage === 'perencanaan' && $currentStatus === 'revision')
            @if($role === 'perencanaan' || $isSuperadmin)
                <p class="text-gray-500 mb-8 max-w-[80%] leading-relaxed">
                    Tim Penyelenggara mengembalikan kegiatan ini untuk direvisi. Setelah diperbaiki, teruskan kembali ke Tim Penyelenggara.
                </p>
                <button onclick="document.getElementById('modalForward').style.display='flex';"
                    class="bg-[#007a7a] hover:bg-[#005f5f] text-white font-semibold px-4 py-2 rounded-lg text-sm transition border-0 cursor-pointer">
                    ➜ Teruskan ke Penyelenggara
                </button>
            @else
                <p class="text-gray-500 mb-8 max-w-[80%] leading-relaxed">
                    Kegiatan sedang direvisi oleh Tim Perencanaan.
                </p>
            @endif

        @elseif($stage === 'penyelenggara' && $currentStatus === 'pending')
            @if($role === 'penyelenggara' || $isSuperadmin)
                <p class="text-gray-500 mb-8 max-w-[80%] leading-relaxed">
                    Kegiatan ini sedang dalam pelaksanaan Tim Penyelenggara.
                </p>
                <div class="flex gap-3">
                    <button onclick="document.getElementById('modalComplete').style.display='flex';"
                        class="bg-[#007a7a] hover:bg-[#005f5f] text-white font-semibold px-4 py-2 rounded-lg text-sm transition border-0 cursor-pointer">
                        ✔ Selesaikan Kegiatan
                    </button>
                    <button onclick="document.getElementById('modalReturnRevision').style.display='flex';"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-4 py-2 rounded-lg text-sm transition border-0 cursor-pointer">
                        ⮌ Kembalikan ke Perencanaan
                    </button>
                </div>
            @else
                <p class="text-gray-500 mb-8 max-w-[80%] leading-relaxed">
                    Kegiatan sedang dilaksanakan oleh Tim Penyelenggara.
                </p>
            @endif

        @elseif($stage === 'evaluasi' && $currentStatus === 'pending')
            @if($role === 'evaluasi' || $isSuperadmin)
                <p class="text-gray-500 mb-8 max-w-[80%] leading-relaxed">
                    Kegiatan telah selesai dilaksanakan dan menunggu evaluasi Anda.
                </p>
                <button onclick="document.getElementById('modalEvaluasiComplete').style.display='flex';"
                    class="bg-[#007a7a] hover:bg-[#005f5f] text-white font-semibold px-4 py-2 rounded-lg text-sm transition border-0 cursor-pointer">
                    ✔ Selesaikan Evaluasi
                </button>
            @else
                <p class="text-gray-500 mb-8 max-w-[80%] leading-relaxed">
                    Kegiatan menunggu evaluasi oleh Tim Evaluasi.
                </p>
            @endif

        @elseif($stage === 'evaluasi' && $currentStatus === 'completed')
            <p class="text-gray-500 mb-8 max-w-[80%] leading-relaxed">
                Kegiatan ini telah selesai dievaluasi dan ditutup. Silakan cek menu <strong class="text-gray-800">Penilaian</strong> untuk melihat rekam jejak persetujuan.
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
            Data usulan akan diteruskan ke Tim Perencanaan. Pastikan semua persyaratan, berkas, dan nama peserta telah lengkap.
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

<!-- MODAL SETUJUI (Perencanaan) -->
<div id="modalApprove"
    class="hidden fixed inset-0 w-full h-full bg-black/50 z-50 items-center justify-center">
    <div class="bg-white rounded-lg p-8 w-[400px] max-w-[90%] text-center shadow-2xl">
        <div class="text-5xl text-teal-500 mb-2">✔</div>
        <h2 class="text-xl font-bold mb-4 text-gray-900">Setujui Usulan?</h2>
        <p class="text-gray-600 mb-8">Usulan ini akan diteruskan ke Tim Penyelenggara untuk pelaksanaan.</p>

        <form action="{{ route('kegiatan.approve', $kegiatan->id) }}" method="POST">
            @csrf
            <div class="text-left mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                <textarea name="note" rows="2" class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-teal-500 focus:outline-none"></textarea>
            </div>
            <div class="flex justify-between gap-4">
                <button type="button" onclick="document.getElementById('modalApprove').style.display='none';"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-4 py-2 rounded-lg text-sm transition flex-1 cursor-pointer border-0">
                    BATAL
                </button>
                <button type="submit"
                    class="bg-[#007a7a] hover:bg-[#005f5f] text-white font-semibold px-4 py-2 rounded-lg text-sm transition flex-1 cursor-pointer border-0">
                    YA, SETUJUI
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL KEMBALIKAN UNTUK REVISI (Perencanaan / Penyelenggara) -->
<div id="modalReturnRevision"
    class="hidden fixed inset-0 w-full h-full bg-black/50 z-50 items-center justify-center">
    <div class="bg-white rounded-lg p-8 w-[400px] max-w-[90%] text-center shadow-2xl">
        <div class="text-5xl text-yellow-500 mb-2">⮌</div>
        <h2 class="text-xl font-bold mb-4 text-gray-900">Kembalikan untuk Revisi?</h2>
        <p class="text-gray-600 mb-6">Jelaskan apa yang perlu diperbaiki. Catatan ini wajib diisi.</p>

        <form action="{{ route('kegiatan.return_revision', $kegiatan->id) }}" method="POST">
            @csrf
            <div class="text-left mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Revisi <span class="text-red-500">*</span></label>
                <textarea name="note" rows="3" required class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none" placeholder="Jelaskan alasan pengembalian..."></textarea>
            </div>
            <div class="flex justify-between gap-4">
                <button type="button" onclick="document.getElementById('modalReturnRevision').style.display='none';"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-4 py-2 rounded-lg text-sm transition flex-1 cursor-pointer border-0">
                    BATAL
                </button>
                <button type="submit"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-4 py-2 rounded-lg text-sm transition flex-1 cursor-pointer border-0">
                    KEMBALIKAN
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL TERUSKAN KE PENYELENGGARA (Perencanaan, setelah revisi selesai) -->
<div id="modalForward"
    class="hidden fixed inset-0 w-full h-full bg-black/50 z-50 items-center justify-center">
    <div class="bg-white rounded-lg p-8 w-[400px] max-w-[90%] text-center shadow-2xl">
        <div class="text-5xl text-teal-500 mb-2">➜</div>
        <h2 class="text-xl font-bold mb-4 text-gray-900">Teruskan ke Penyelenggara?</h2>
        <p class="text-gray-600 mb-8">Pastikan revisi yang diminta sudah diperbaiki.</p>

        <form action="{{ route('kegiatan.perencanaan_forward', $kegiatan->id) }}" method="POST">
            @csrf
            <div class="text-left mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                <textarea name="note" rows="2" class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-teal-500 focus:outline-none"></textarea>
            </div>
            <div class="flex justify-between gap-4">
                <button type="button" onclick="document.getElementById('modalForward').style.display='none';"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-4 py-2 rounded-lg text-sm transition flex-1 cursor-pointer border-0">
                    BATAL
                </button>
                <button type="submit"
                    class="bg-[#007a7a] hover:bg-[#005f5f] text-white font-semibold px-4 py-2 rounded-lg text-sm transition flex-1 cursor-pointer border-0">
                    TERUSKAN
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL SELESAIKAN KEGIATAN (Penyelenggara) -->
<div id="modalComplete"
    class="hidden fixed inset-0 w-full h-full bg-black/50 z-50 items-center justify-center">
    <div class="bg-white rounded-lg p-8 w-[400px] max-w-[90%] text-center shadow-2xl">
        <div class="text-5xl text-teal-500 mb-2">✔</div>
        <h2 class="text-xl font-bold mb-4 text-gray-900">Selesaikan Kegiatan?</h2>
        <p class="text-gray-600 mb-8">Kegiatan akan diteruskan ke Tim Evaluasi.</p>

        <form action="{{ route('kegiatan.penyelenggara_complete', $kegiatan->id) }}" method="POST">
            @csrf
            <div class="text-left mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                <textarea name="note" rows="2" class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-teal-500 focus:outline-none"></textarea>
            </div>
            <div class="flex justify-between gap-4">
                <button type="button" onclick="document.getElementById('modalComplete').style.display='none';"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-4 py-2 rounded-lg text-sm transition flex-1 cursor-pointer border-0">
                    BATAL
                </button>
                <button type="submit"
                    class="bg-[#007a7a] hover:bg-[#005f5f] text-white font-semibold px-4 py-2 rounded-lg text-sm transition flex-1 cursor-pointer border-0">
                    YA, SELESAI
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL SELESAIKAN EVALUASI -->
<div id="modalEvaluasiComplete"
    class="hidden fixed inset-0 w-full h-full bg-black/50 z-50 items-center justify-center">
    <div class="bg-white rounded-lg p-8 w-[400px] max-w-[90%] text-center shadow-2xl">
        <div class="text-5xl text-teal-500 mb-2">✔</div>
        <h2 class="text-xl font-bold mb-4 text-gray-900">Selesaikan Evaluasi?</h2>
        <p class="text-gray-600 mb-8">Kegiatan akan ditutup setelah evaluasi diselesaikan.</p>

        <form action="{{ route('kegiatan.evaluasi_complete', $kegiatan->id) }}" method="POST">
            @csrf
            <div class="text-left mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                <textarea name="note" rows="2" class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-teal-500 focus:outline-none"></textarea>
            </div>
            <div class="flex justify-between gap-4">
                <button type="button" onclick="document.getElementById('modalEvaluasiComplete').style.display='none';"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-4 py-2 rounded-lg text-sm transition flex-1 cursor-pointer border-0">
                    BATAL
                </button>
                <button type="submit"
                    class="bg-[#007a7a] hover:bg-[#005f5f] text-white font-semibold px-4 py-2 rounded-lg text-sm transition flex-1 cursor-pointer border-0">
                    YA, SELESAI
                </button>
            </div>
        </form>
    </div>
</div>
