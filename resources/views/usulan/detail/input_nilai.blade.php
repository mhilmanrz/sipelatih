<section style="margin-top: 2rem;">

    @push('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/InputNilai.css') }}">
    <style>
        /* Modifikasi style tabel agar selaras dengan tabel peserta tanpa menimpa style page */
        .input-nilai-table th {
            background-color: #f3f4f6;
            color: #374151;
            border-color: #e5e7eb;
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        .input-nilai-table td {
            color: #4b5563;
            border-color: #e5e7eb;
        }

        /* Penyesuaian modal khusus Input Nilai */
        #modal {
            z-index: 100 !important;
        }
    </style>
    @endpush

    <!-- TITLE / HIDDEN VARS -->
    <div style="font-weight: bold; font-size: 1.125rem; margin-bottom: 1.5rem; color: #111827;">
        Input Nilai Peserta
    </div>

    <input type="hidden" id="kegiatanId" value="{{ $kegiatan->id }}">

    <!-- ACTION BUTTON -->
    <div class="flex flex-wrap gap-4 mb-6">
        <!-- Disabled Tambah dan Edit bulk for now, focus on Edit per participant if needed or keep existing script -->
    </div>

    <!-- CARD -->
    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 2rem;">

        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1000px] text-sm border-collapse border border-gray-200 input-nilai-table">
                <thead>
                    <tr>
                        <th class="border px-4 py-3 text-center">NO.</th>
                        <th class="border px-4 py-3 text-left">Nama Peserta</th>
                        <th class="border px-4 py-3 text-center">NIP/NPS</th>
                        <th class="border px-4 py-3 text-left">Unit Kerja</th>
                        <th class="border px-4 py-3 text-center">Pre Test</th>
                        <th class="border px-4 py-3 text-center">Post Test</th>
                        <th class="border px-4 py-3 text-center">Nilai Praktik</th>
                        <th class="border px-4 py-3 text-center">Akumulasi</th>
                        <th class="border px-4 py-3 text-center">Batas Lulus</th>
                        <th class="border px-4 py-3 text-center">Status</th>
                        <th class="border px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody id="tableBody" class="bg-white">

                    @forelse($kegiatan->activityParticipants as $index => $participant)
                    @php
                    $pre = $participant->score->pre_test_score ?? 0;
                    $post = $participant->score->post_test_score ?? 0;
                    $praktik = $participant->score->practice_score ?? 0;
                    $akumulasi = collect([$participant->score->pre_test_score, $participant->score->post_test_score, $participant->score->practice_score])->filter(fn($v) => !is_null($v))->average() ?? 0;
                    $akumulasi = round($akumulasi);
                    $batas = 80;
                    $isPassed = $participant->is_passed;
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors" data-participant-id="{{ $participant->id }}">
                        <td class="border px-4 py-3 text-center">{{ $index + 1 }}</td>
                        <td class="border px-4 py-3 font-medium">{{ $participant->user->name ?? '-' }}</td>
                        <td class="border px-4 py-3 text-center">{{ $participant->user->employee_id ?? '-' }}</td>
                        <td class="border px-4 py-3">{{ optional($participant->user->workUnit)->name ?? '-' }}</td>
                        <td class="border px-4 py-3 text-center">{{ $pre }}</td>
                        <td class="border px-4 py-3 text-center">{{ $post }}</td>
                        <td class="border px-4 py-3 text-center">{{ $praktik }}</td>
                        <td class="border px-4 py-3 text-center akumulasi-label font-bold text-gray-700">{{ $akumulasi }}</td>
                        <td class="border px-4 py-3 text-center text-gray-500">{{ $batas }}</td>
                        <td class="border px-4 py-3 text-center status-container">
                            @if($isPassed === 1)
                            <span class="bg-green-100 text-green-700 border border-green-200 px-3 py-1 rounded text-xs font-bold uppercase shadow-sm">
                                Lulus
                            </span>
                            @elseif($isPassed === 0)
                            <span class="bg-red-100 text-red-700 border border-red-200 px-3 py-1 rounded text-xs font-bold uppercase shadow-sm">
                                Tidak Lulus
                            </span>
                            @else
                            <span class="bg-gray-100 text-gray-500 border border-gray-200 px-3 py-1 rounded text-xs uppercase shadow-sm">
                                Belum Diuji
                            </span>
                            @endif
                        </td>
                        <td class="border px-4 py-3 text-center">
                            <button type="button" class="bg-teal-500 hover:bg-teal-600 text-white font-semibold flex items-center justify-center gap-1 px-3 py-1.5 rounded shadow-sm text-xs transition-colors editBtn mx-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                                Isi / Edit
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="border px-4 py-8 text-center text-gray-500 font-medium">Belum ada peserta didaftarkan pada kegiatan ini.</td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <!-- FOOTER -->
        <div style="display: flex; justify-content: space-between; margin-top: 1.5rem; align-items: center; font-size: 0.875rem; color: #6b7280;">
            <span>Menampilkan total {{ $kegiatan->activityParticipants->count() }} peserta</span>
        </div>

    </div>

    <!-- MODAL EDIT NILAI -->
    <div id="modal" class="modal hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm justify-center items-center z-[100]" style="z-index: 9999;">
        <div class="modal-content bg-white p-8 rounded-3xl w-full max-w-xl shadow-2xl relative">
            <button id="closeModal" type="button" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 border-2 border-gray-400 rounded-full w-8 h-8 flex items-center justify-center font-bold pb-1 transition-colors z-10">&times;</button>

            <form id="nilaiForm" class="mt-4">

                <!-- ROW 1: Nama Peserta -->
                <div class="flex items-center gap-6 mb-6">
                    <label class="w-1/3 text-sm font-bold text-teal-600 text-right">Nama Peserta</label>
                    <div class="w-2/3 pr-8">
                        <input type="text" id="nama" class="w-full border-2 border-teal-600 px-4 py-1.5 rounded-full focus:outline-none font-medium text-gray-700 shadow-sm" required readonly>
                    </div>
                </div>

                <!-- ROW 2: NIP/NPS -->
                <div class="flex items-center gap-6 mb-6">
                    <label class="w-1/3 text-sm font-bold text-teal-600 text-right">NIP/NPS</label>
                    <div class="w-2/3 pr-8">
                        <input type="text" id="nip" class="w-full border-2 border-teal-600 px-4 py-1.5 rounded-full focus:outline-none font-medium text-gray-700 shadow-sm" required readonly>
                    </div>
                </div>

                <!-- ROW 3: Unit Kerja -->
                <div class="flex items-center gap-6 mb-8">
                    <label class="w-1/3 text-sm font-bold text-teal-600 text-right">Unit Kerja</label>
                    <div class="w-2/3 pr-8">
                        <input type="text" id="unit" class="w-full border-2 border-teal-600 px-4 py-1.5 rounded-full focus:outline-none font-medium text-gray-700 shadow-sm" required readonly>
                    </div>
                </div>

                <hr class="border-t-2 border-teal-400 my-8 -mx-8">

                <!-- ROW 4: Pre Test -->
                <div class="flex items-center gap-6 mb-6">
                    <label class="w-1/3 text-sm font-bold text-teal-600 text-right">Pre Test</label>
                    <div class="w-1/3">
                        <input type="number" id="pretest" min="0" max="100" class="w-full border-2 border-teal-600 px-4 py-1.5 rounded-full focus:outline-none focus:ring-2 focus:ring-teal-300 font-medium text-gray-700 text-center shadow-sm" placeholder="0 - 100" required>
                    </div>
                </div>

                <!-- ROW 5: Post Test -->
                <div class="flex items-center gap-6 mb-6">
                    <label class="w-1/3 text-sm font-bold text-teal-600 text-right">Post Test</label>
                    <div class="w-1/3">
                        <input type="number" id="posttest" min="0" max="100" class="w-full border-2 border-teal-600 px-4 py-1.5 rounded-full focus:outline-none focus:ring-2 focus:ring-teal-300 font-medium text-gray-700 text-center shadow-sm" placeholder="0 - 100" required>
                    </div>
                </div>

                <!-- ROW 6: Nilai Praktik -->
                <div class="flex items-center gap-6 mb-10">
                    <label class="w-1/3 text-sm font-bold text-teal-600 text-right">Nilai Praktik</label>
                    <div class="w-1/3">
                        <input type="number" id="praktik" min="0" max="100" class="w-full border-2 border-teal-600 px-4 py-1.5 rounded-full focus:outline-none focus:ring-2 focus:ring-teal-300 font-medium text-gray-700 text-center shadow-sm" placeholder="0 - 100" required>
                    </div>
                </div>

                <!-- BUTTONS -->
                <div class="flex justify-center gap-4 mt-2">
                    <button type="submit" class="flex items-center gap-2 px-6 py-2 bg-teal-600 text-white rounded-full hover:bg-teal-700 transition-colors font-bold shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        SIMPAN
                    </button>
                    <button type="button" id="cancelModal" class="flex items-center gap-2 px-6 py-2 bg-gray-400 text-white rounded-full hover:bg-gray-500 transition-colors font-bold shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 border-2 border-white rounded-full p-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        BATAL
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

@push('scripts')
<script src="{{ asset('assets/js/InputNilai.js') }}?v={{ time() }}"></script>

@endpush