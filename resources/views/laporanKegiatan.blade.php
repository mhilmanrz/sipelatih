@extends('layout.LayoutSuperAdmin')

@section('title', 'Laporan Kegiatan')

@push('styles')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/LayoutSuperAdmin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 24px;
            border: 1px solid #888;
            width: 90%;
            max-width: 500px;
            border-radius: 8px;
            position: relative;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }
    </style>
@endpush

@section('content')
    <div class="p-8">
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold uppercase" style="color: white; font-size: 1.5rem;padding: 1rem 2rem; border-radius: 8px; display: inline-block;">LAPORAN KEGIATAN</h1>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow p-6 mb-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">DAFTAR LAPORAN KEGIATAN</h2>
                <a href="{{ route('kegiatan.laporan.template') }}" class="inline-flex py-2 px-4 bg-[#D6DE20] hover:bg-[#006bd6] text-black font-semibold rounded shadow items-center transition-colors text-sm">
                    <i class="fas fa-download mr-2"></i> Download Template
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#1A5555] text-white">
                            <th class="border px-4 py-2 border-gray-300 w-16 text-center">NO.</th>
                            <th class="border px-4 py-2 border-gray-300">Nama Kegiatan</th>
                            <th class="border px-4 py-2 border-gray-300 w-32 text-center">Tgl Mulai</th>
                            <th class="border px-4 py-2 border-gray-300 w-32 text-center">Tgl Selesai</th>
                            <th class="border px-4 py-2 border-gray-300 w-48 text-center">Laporan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $index => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2 border-gray-300 text-center">{{ $index + 1 }}</td>
                            <td class="border px-4 py-2 border-gray-300">{{ $item->activityName->name ?? $item->reference_number ?? '-' }}</td>
                            <td class="border px-4 py-2 border-gray-300 text-center">{{ $item->start_date }}</td>
                            <td class="border px-4 py-2 border-gray-300 text-center">{{ $item->end_date }}</td>
                            <td class="border px-4 py-2 border-gray-300 text-center">
                                @if($item->report)
                                    <button onclick="openModal({{ $item->id }}, {{ $item->report->id }}, '{{ addslashes($item->activityName->name ?? $item->reference_number ?? '-') }}')" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm mr-1">
                                        <i class="fas fa-edit"></i> Ubah
                                    </button>
                                    <a href="{{ Storage::url($item->report->file_path) }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm inline-block">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                @else
                                    <button onclick="openModal({{ $item->id }}, null, '{{ addslashes($item->activityName->name ?? $item->reference_number ?? '-') }}')" class="bg-teal-500 hover:bg-teal-600 text-white px-3 py-1 rounded text-sm">
                                        <i class="fas fa-upload"></i> Upload
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="border px-4 py-2 border-gray-300 text-center text-gray-500">Tidak ada data kegiatan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Upload/Edit Modal -->
    <div id="laporanModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle" class="text-xl font-bold mb-4">Upload Laporan</h2>
            
            <form id="laporanForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="methodContainer"></div>
                
                <input type="hidden" name="activity_id" id="activity_id">
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Kegiatan</label>
                    <input type="text" id="activity_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight bg-gray-100 cursor-not-allowed" readonly>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">File Laporan</label>
                    <input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx" required class="w-full text-gray-700 border rounded py-2 px-3">
                    <p class="text-xs text-gray-500 mt-1">Maksimal ukuran file: 10MB (PDF, DOC/X, XLS/X)</p>
                </div>

                <div class="flex justify-end">
                    <button type="button" onclick="closeModal()" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded mr-2">
                        Batal
                    </button>
                    <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openModal(activityId, reportId, activityName) {
        document.getElementById('activity_id').value = activityId;
        document.getElementById('activity_name').value = activityName;
        
        let form = document.getElementById('laporanForm');
        let methodContainer = document.getElementById('methodContainer');
        let modalTitle = document.getElementById('modalTitle');
        
        if (reportId) {
            // Edit mode
            modalTitle.innerText = "Ubah Laporan";
            form.action = "{{ url('laporan-kegiatan') }}/" + reportId;
            methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        } else {
            // Upload mode
            modalTitle.innerText = "Upload Laporan";
            form.action = "{{ route('kegiatan.laporan.store') }}";
            methodContainer.innerHTML = '';
        }
        
        document.getElementById('laporanModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('laporanModal').style.display = 'none';
        document.getElementById('laporanForm').reset();
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        let modal = document.getElementById('laporanModal');
        if (event.target == modal) {
            closeModal();
        }
    }
</script>
@endpush
