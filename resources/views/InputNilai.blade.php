@extends('layout.LayoutPenyelenggaraan')

@section('title', 'Input Nilai')

@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('css/LayoutPenyelenggaraan.css') }}">
<link rel="stylesheet" href="{{ asset('css/InputNilai.css') }}">
@endpush

@section('content')

<div class="content input-nilai-page bg-[#14B8C5] min-h-screen p-6">

    <h1 class="text-white text-3xl font-bold mb-8 uppercase">
        INPUT NILAI PESERTA: {{ $kegiatan->name ?? 'Kegiatan' }}
    </h1>

    <input type="hidden" id="kegiatanId" value="{{ $kegiatan->id }}">

    <!-- ACTION BUTTON -->
    <div class="flex flex-wrap gap-4 mb-8">

        <button id="openModal"
            class="bg-[#006D73] text-yellow-300 px-6 py-3 rounded-full flex items-center gap-2 shadow hover:opacity-90">
            <i class="fa-solid fa-plus"></i>
            Tambah
        </button>

        <button
            class="bg-[#1C9AA3] text-white px-6 py-3 rounded-full flex items-center gap-2 shadow hover:opacity-90">
            <i class="fa-solid fa-pen"></i>
            Edit
        </button>

    </div>

    <!-- TABLE -->
    <div class="bg-[#007C82] rounded-[20px] shadow overflow-x-auto">

        <table class="w-full min-w-[1000px] text-sm">
            <thead class="bg-[#007C82] text-white">
                <tr>
                    <th class="py-4 px-3 text-center">NO.</th>
                    <th class="py-4 px-3">Nama Peserta</th>
                    <th class="py-4 px-3 text-center">NIP/NPS</th>
                    <th class="py-4 px-3 text-center">Unit Kerja</th>
                    <th class="py-4 px-3 text-center">Pre Test</th>
                    <th class="py-4 px-3 text-center">Post Test</th>
                    <th class="py-4 px-3 text-center">Nilai Praktik</th>
                    <th class="py-4 px-3 text-center">Akumulasi</th>
                    <th class="py-4 px-3 text-center">Batas Lulus</th>
                    <th class="py-4 px-3 text-center">Status</th>
                    <th class="py-4 px-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody id="tableBody" class="bg-white text-gray-800">

                @forelse($participants as $index => $participant)
                @php
                    $pre = $participant->score->pre_test_score ?? 0;
                    $post = $participant->score->post_test_score ?? 0;
                    $praktik = $participant->score->practice_score ?? 0;
                    // Note: jika ketiga nilai null, akumulasi akan 0. 
                    $akumulasi = collect([$participant->score->pre_test_score, $participant->score->post_test_score, $participant->score->practice_score])->filter(fn($v) => !is_null($v))->average() ?? 0;
                    $akumulasi = round($akumulasi);
                    $batas = 80;
                    $isPassed = $participant->is_passed;
                @endphp
                <tr class="border-b" data-participant-id="{{ $participant->id }}">
                    <td class="py-4 text-center">{{ $index + 1 }}</td>
                    <td class="py-4">{{ $participant->user->name ?? '-' }}</td>
                    <td class="py-4 text-center">{{ $participant->user->nip ?? '-' }}</td>
                    <td class="py-4 text-center">{{ optional($participant->user->workUnit)->name ?? '-' }}</td>
                    <td class="py-4 text-center">{{ $pre }}</td>
                    <td class="py-4 text-center">{{ $post }}</td>
                    <td class="py-4 text-center">{{ $praktik }}</td>
                    <td class="py-4 text-center akumulasi-label">{{ $akumulasi }}</td>
                    <td class="py-4 text-center">{{ $batas }}</td>
                    <td class="py-4 text-center status-container">
                        @if($isPassed)
                        <span class="bg-green-600 text-white px-3 py-1 rounded-full text-xs">
                            Lulus
                        </span>
                        @else
                        <span class="bg-red-600 text-white px-3 py-1 rounded-full text-xs">
                            Tidak Lulus
                        </span>
                        @endif
                    </td>
                    <td class="py-4 text-center">
                        <button type="button" class="bg-[#1C9AA3] text-white px-4 py-1 rounded-full text-xs shadow editBtn">
                            <i class="fa-solid fa-pen mr-1"></i> Edit
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="py-8 text-center text-gray-500">Belum ada peserta didaftarkan pada kegiatan ini.</td>
                </tr>
                @endforelse

            </tbody>
        </table>

    </div>

    <!-- FOOTER -->
    <div class="flex flex-wrap gap-3 justify-between items-center mt-6 text-white text-sm">
        <span>Menampilkan total {{ count($participants) }} peserta</span>
    </div>

    <!-- MODAL (ADDED BY ASSISTANT) -->
    <div id="modal" class="modal hidden fixed inset-0 bg-black bg-opacity-50 z-50 justify-center items-center">
        <div class="modal-content bg-white p-6 rounded-lg w-full max-w-lg shadow-lg relative">
            <button id="closeModal" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 text-2xl font-bold">&times;</button>
            <h2 class="text-xl font-bold mb-6 text-gray-800 border-b pb-2">Form Input Nilai</h2>
            
            <form id="nilaiForm">
                <div class="form-group mb-4">
                    <label class="block text-sm font-semibold mb-1 text-gray-700">Nama Peserta</label>
                    <input type="text" id="nama" class="w-full border px-3 py-2 rounded-lg focus:outline-none border-teal-500" required>
                </div>
                <div class="flex gap-4 mb-4">
                    <div class="form-group flex-1">
                        <label class="block text-sm font-semibold mb-1 text-gray-700">NIP / NPS</label>
                        <input type="text" id="nip" class="w-full border px-3 py-2 rounded-lg focus:outline-none border-teal-500" required>
                    </div>
                    <div class="form-group flex-1">
                        <label class="block text-sm font-semibold mb-1 text-gray-700">Unit Kerja</label>
                        <input type="text" id="unit" class="w-full border px-3 py-2 rounded-lg focus:outline-none border-teal-500" required>
                    </div>
                </div>
                
                <div class="form-row flex gap-4 mb-6">
                    <div class="flex-1">
                        <label class="block text-sm font-semibold mb-1 text-gray-700">Pre Test</label>
                        <select id="pretest" class="w-full border px-3 py-2 rounded-lg focus:outline-none border-teal-500" required>
                            <option value="">-ilih-</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-semibold mb-1 text-gray-700">Post Test</label>
                        <select id="posttest" class="w-full border px-3 py-2 rounded-lg focus:outline-none border-teal-500" required>
                            <option value="">-Pilih-</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-semibold mb-1 text-gray-700">Praktik</label>
                        <select id="praktik" class="w-full border px-3 py-2 rounded-lg focus:outline-none border-teal-500" required>
                            <option value="">-Pilih-</option>
                        </select>
                    </div>
                </div>

                <div class="modal-actions flex justify-end gap-3 mt-6 border-t pt-4">
                    <button type="button" id="cancelModal" class="batal px-6 py-2 bg-gray-400 text-white rounded-full hover:bg-gray-500 transition font-medium shadow">Tutup</button>
                    <button type="submit" class="simpan px-6 py-2 bg-teal-600 text-white rounded-full hover:bg-teal-700 transition font-medium shadow">Simpan Nilai</button>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection


@push('scripts')
<script src="{{ asset('js/LayoutPenyelenggaraan.js') }}"></script>
<script src="{{ asset('js/InputNilai.js') }}"></script>
@endpush