<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Act\Activity;
use App\Models\Act\ActivityParticipant;
use App\Models\User\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ParticipantImport;
use App\Exports\ParticipantTemplateExport;

class ActivityParticipantController extends Controller
{
    /**
     * Tampilkan halaman API untuk pencarian pengguna yang tersedia.
     */
    public function availableUsers(Request $request, $kegiatanId)
    {
        $search = $request->input('search');

        // Ambil ID peserta yang sudah terdaftar di kegiatan ini
        $existingUserIds = ActivityParticipant::where('activity_id', $kegiatanId)->pluck('user_id')->toArray();

        // Query pengguna yang BUKAN peserta
        $query = User::whereNotIn('id', $existingUserIds);

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('employee_id', 'like', '%' . $search . '%');
            });
        }

        // Return Data pagination API JSON
        $users = $query->paginate(10);

        return response()->json($users);
    }

    /**
     * Show the form for creating a new participant (Dual Pane UI).
     */
    public function create($kegiatanId)
    {
        $kegiatan = Activity::findOrFail($kegiatanId);
        
        return view('usulan.detail.tambah_peserta', compact('kegiatan'));
    }

    /**
     * Store new participants in storage.
     */
    public function store(Request $request, $kegiatanId)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $activity = Activity::findOrFail($kegiatanId);

        foreach ($request->user_ids as $userId) {
            // Hindari duplikasi jika front-end gagal filter
            $exists = ActivityParticipant::where('activity_id', $activity->id)
                        ->where('user_id', $userId)
                        ->exists();

            if (!$exists) {
                ActivityParticipant::create([
                    'activity_id' => $activity->id,
                    'user_id' => $userId,
                    'is_passed' => false, // default
                ]);
            }
        }

        return redirect()->route('kegiatan.show', ['kegiatan' => $activity->id, 'tab' => 'peserta'])
            ->with('success', 'Peserta berhasil ditambahkan.');
    }

    /**
     * Remove the specified participant from storage.
     */
    public function destroy($kegiatanId, $id)
    {
        $participant = ActivityParticipant::where('activity_id', $kegiatanId)
            ->findOrFail($id);
            
        $participant->delete();

        return redirect()->route('kegiatan.show', ['kegiatan' => $kegiatanId, 'tab' => 'peserta'])
            ->with('success', 'Peserta berhasil dihapus.');
    }

    /**
     * Show the form to import participants.
     */
    public function importPage($kegiatanId)
    {
        $kegiatan = Activity::findOrFail($kegiatanId);
        return view('usulan.detail.import_peserta', compact('kegiatan'));
    }

    /**
     * Process excel file and import.
     */
    public function importStore(Request $request, $kegiatanId)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        $activity = Activity::findOrFail($kegiatanId);

        try {
            Excel::import(new ParticipantImport($activity->id), $request->file('file'));
            return redirect()->route('kegiatan.show', ['kegiatan' => $activity->id, 'tab' => 'peserta'])
                ->with('success', 'Import peserta berhasil. Data NIP tidak terdaftar telah diabaikan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat meng-import data: ' . $e->getMessage());
        }
    }

    /**
     * Download Excel template.
     */
    public function downloadTemplate()
    {
        return Excel::download(new ParticipantTemplateExport, 'Template_Import_Peserta.xlsx');
    }
}
