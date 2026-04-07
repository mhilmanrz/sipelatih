<?php

namespace App\Http\Controllers\Act;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Act\ActivityScore;

class ActivityScoreController extends Controller
{
    /**
     * Display the score input page for a specific activity.
     */
    public function index($kegiatanId)
    {
        $kegiatan = \App\Models\Act\Activity::findOrFail($kegiatanId);

        // Ambil semua peserta di kegiatan ini beserta data user, work_unit, dan score (jika ada)
        $participants = \App\Models\Act\ActivityParticipant::with(['user.workUnit', 'score'])
            ->where('activity_id', $kegiatanId)
            ->get();

        return view('InputNilai', compact('kegiatan', 'participants'));
    }

    /**
     * Store or update score for a participant via AJAX.
     */
    public function storeOrUpdate(Request $request, $kegiatanId, $participantId)
    {
        $request->validate([
            'pre_test_score' => 'nullable|integer|min:0|max:100',
            'post_test_score' => 'nullable|integer|min:0|max:100',
            'practice_score' => 'nullable|integer|min:0|max:100',
        ]);

        $participant = \App\Models\Act\ActivityParticipant::where('id', $participantId)
            ->where('activity_id', $kegiatanId)
            ->firstOrFail();

        // Cari existing score atau buat baru
        $score = ActivityScore::firstOrNew(['activity_participant_id' => $participant->id]);

        $score->pre_test_score = $request->input('pre_test_score');
        $score->post_test_score = $request->input('post_test_score');
        $score->practice_score = $request->input('practice_score');
        $score->save();

        // Hitung status kelulusan (akumulasi >= 80)
        $akumulasi = collect([$score->pre_test_score, $score->post_test_score, $score->practice_score])->average();
        
        $batas = 80; // Hardcode untuk sekarang, atau bisa diambil dari setting
        $isPassed = $akumulasi >= $batas;

        $participant->update(['is_passed' => $isPassed]);

        return response()->json([
            'success' => true,
            'message' => 'Nilai berhasil disimpan.',
            'data' => [
                'akumulasi' => round($akumulasi),
                'status' => $isPassed ? 'Lulus' : 'Tidak Lulus',
                'warna' => $isPassed ? 'bg-green-600' : 'bg-red-600'
            ]
        ]);
    }
}
