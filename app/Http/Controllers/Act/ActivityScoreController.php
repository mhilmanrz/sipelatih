<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Models\Act\ActivityParticipant;
use App\Models\Act\ActivityScore;
use Illuminate\Http\Request;

class ActivityScoreController extends Controller
{
    public function update(Request $request, $kegiatan_id, $participant_id)
    {
        $request->validate([
            'pre_test_score' => 'nullable|integer|min:0|max:100',
            'post_test_score' => 'nullable|integer|min:0|max:100',
            'practice_score' => 'nullable|integer|min:0|max:100',
            'is_passed' => 'required|boolean',
        ]);

        $participant = ActivityParticipant::where('activity_id', $kegiatan_id)
            ->findOrFail($participant_id);

        $participant->update([
            'is_passed' => $request->is_passed,
        ]);

        ActivityScore::updateOrCreate(
            ['activity_participant_id' => $participant->id],
            [
                'pre_test_score' => $request->pre_test_score,
                'post_test_score' => $request->post_test_score,
                'practice_score' => $request->practice_score,
            ]
        );

        return back()->with('success', 'Nilai peserta berhasil diperbarui.');
    }
}
