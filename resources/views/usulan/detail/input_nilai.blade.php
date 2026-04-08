<section style="margin-top: 2rem;">

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
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

    <!-- CARD -->
    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 2rem;">
        
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">Input Nilai Peserta</h2>
        </div>

        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse border border-gray-200" style="min-width: 1000px;">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-gray-300 px-2 py-2 text-center text-gray-700 w-12">NO.</th>
                        <th class="border border-gray-300 px-4 py-2 text-left text-gray-700">Nama Peserta</th>
                        <th class="border border-gray-300 px-4 py-2 text-left text-gray-700">NIP/NPS</th>
                        <th class="border border-gray-300 px-4 py-2 text-left text-gray-700">Unit Kerja</th>
                        <th class="border border-gray-300 px-2 py-2 text-center text-gray-700 w-20">Pre Test</th>
                        <th class="border border-gray-300 px-2 py-2 text-center text-gray-700 w-20">Post Test</th>
                        <th class="border border-gray-300 px-2 py-2 text-center text-gray-700 w-20">Praktik</th>
                        <th class="border border-gray-300 px-2 py-2 text-center text-gray-700 w-24">Akumulasi</th>
                        <th class="border border-gray-300 px-2 py-2 text-center text-gray-700 w-24">Batas Lulus</th>
                        <th class="border border-gray-300 px-4 py-2 text-center text-gray-700 w-24">Status</th>
                        <th class="border border-gray-300 px-4 py-2 text-center text-gray-700 w-20">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse ($kegiatan->activityParticipants as $index => $participant)
                        @php
                            $score = $participant->score;
                            $pre = $score?->pre_test_score ?? 0;
                            $post = $score?->post_test_score ?? 0;
                            $praktik = $score?->practice_score ?? 0;
                            $average = round(($pre + $post + $praktik) / 3, 2);
                        @endphp
                        <tr>
                            <td class="border border-gray-300 px-2 py-2 text-center">{{ $index + 1 }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $participant->user->name ?? '-' }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $participant->user->nip ?? '-' }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $participant->user->workUnit->name ?? '-' }}</td>
                            <td class="border border-gray-300 px-2 py-2 text-center font-semibold">{{ $pre }}</td>
                            <td class="border border-gray-300 px-2 py-2 text-center font-semibold">{{ $post }}</td>
                            <td class="border border-gray-300 px-2 py-2 text-center font-semibold">{{ $praktik }}</td>
                            <td class="border border-gray-300 px-2 py-2 text-center font-bold {{ $average >= 80 ? 'text-green-600' : 'text-red-600' }}">{{ $average }}</td>
                            <td class="border border-gray-300 px-2 py-2 text-center font-semibold text-gray-600">80</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                @if($participant->is_passed)
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 px-1 rounded border border-green-400">Lulus</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 px-1 rounded border border-red-400">Tidak Lulus</span>
                                @endif
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <button type="button" 
                                    onclick="openScoreModal({{ $participant->id }}, '{{ addslashes($participant->user->name ?? '-') }}', {{ $pre }}, {{ $post }}, {{ $praktik }}, {{ $participant->is_passed ? 'true' : 'false' }})" 
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs transition-colors font-semibold">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="border border-gray-300 px-4 py-4 text-center text-gray-500">Belum ada peserta di kegiatan ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Modal Input Nilai -->
<style>
    .score-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.5);
        align-items: center;
        justify-content: center;
    }
    .score-modal.active {
        display: flex;
    }
    .score-modal-content {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        width: 100%;
        max-width: 500px;
        overflow: hidden;
    }
    .score-modal-header {
        background-color: #0d9488;
        color: white;
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: bold;
        font-size: 1.1rem;
    }
    .score-modal-close {
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
        line-height: 1;
    }
    .score-modal-body {
        padding: 20px;
    }
    .score-form-group {
        margin-bottom: 15px;
    }
    .score-form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        font-size: 0.9rem;
        color: #374151;
    }
    .score-form-control {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        font-size: 0.95rem;
    }
    .score-form-control[readonly] {
        background-color: #f3f4f6;
        cursor: not-allowed;
    }
    .score-grid-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 15px;
    }
    .score-modal-footer {
        padding: 15px 20px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }
    .btn-cancel {
        background-color: #9ca3af;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
    }
    .btn-save {
        background-color: #0d9488;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
    }
</style>

<div id="scoreModal" class="score-modal">
    <div class="score-modal-content">
        <div class="score-modal-header">
            <span>Input Nilai Peserta</span>
            <span class="score-modal-close" onclick="closeScoreModal()">&times;</span>
        </div>
        
        <form id="scoreForm" method="POST">
            @csrf
            @method('PUT')
            
            <div class="score-modal-body">
                <div class="score-form-group">
                    <label>Nama Peserta</label>
                    <input type="text" id="modal_participant_name" class="score-form-control" readonly>
                </div>
                
                <div class="score-grid-3 score-form-group">
                    <div>
                        <label>Pre Test</label>
                        <input type="number" name="pre_test_score" id="modal_pre" min="0" max="100" class="score-form-control" required>
                    </div>
                    <div>
                        <label>Post Test</label>
                        <input type="number" name="post_test_score" id="modal_post" min="0" max="100" class="score-form-control" required>
                    </div>
                    <div>
                        <label>Praktik</label>
                        <input type="number" name="practice_score" id="modal_praktik" min="0" max="100" class="score-form-control" required>
                    </div>
                </div>

                <div class="score-form-group">
                    <label>Status Kelulusan</label>
                    <select name="is_passed" id="modal_status" class="score-form-control" required>
                        <option value="1">Lulus</option>
                        <option value="0">Tidak Lulus</option>
                    </select>
                    <p style="font-size: 0.8rem; color: #6b7280; margin-top: 5px;">* Batas Nilai Kelulusan (Akumulasi Rata-rata) adalah 80.</p>
                </div>
            </div>
            
            <div class="score-modal-footer">
                <button type="button" onclick="closeScoreModal()" class="btn-cancel">Batal</button>
                <button type="submit" class="btn-save">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openScoreModal(participantId, participantName, preTest, postTest, praktik, isPassed) {
        document.getElementById('modal_participant_name').value = participantName;
        document.getElementById('modal_pre').value = preTest;
        document.getElementById('modal_post').value = postTest;
        document.getElementById('modal_praktik').value = praktik;
        
        document.getElementById('modal_status').value = isPassed ? "1" : "0";
        
        // Update Form Action URL
        let form = document.getElementById('scoreForm');
        let baseUrl = "{{ route('kegiatan.input-nilai.update', ['kegiatan' => $kegiatan->id, 'participant_id' => ':id']) }}";
        form.action = baseUrl.replace(':id', participantId);
        
        document.getElementById('scoreModal').classList.add('active');
    }

    function closeScoreModal() {
        document.getElementById('scoreModal').classList.remove('active');
    }
</script>
