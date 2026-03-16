<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreActivityParticipantRequest;
use App\Http\Requests\UpdateActivityParticipantRequest;
use App\Imports\ActivityParticipantsImport;
use App\Models\Act\ActivityParticipant;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ActivityParticipantController extends Controller
{
    protected $relations = ['user.workUnit', 'user.profession'];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activityParticipants = ActivityParticipant::with($this->relations)->paginate(10);
        return response()->json($activityParticipants);
    }

    /**
     * Get all participants for a specific activity.
     */
    public function getByActivity($activityId)
    {
        $participants = ActivityParticipant::with($this->relations)
            ->where('activity_id', $activityId)
            ->get();

        return response()->json($participants);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreActivityParticipantRequest $request)
    {
        $activityParticipant = ActivityParticipant::create($request->validated());
        $activityParticipant->load($this->relations);

        return response()->json($activityParticipant, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $activityParticipant = ActivityParticipant::with($this->relations)->find($id);

        if (!$activityParticipant) {
            return response()->json(['message' => 'Activity Participant not found'], 404);
        }

        return response()->json($activityParticipant);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActivityParticipantRequest $request, string $id)
    {
        $activityParticipant = ActivityParticipant::find($id);

        if (!$activityParticipant) {
            return response()->json(['message' => 'Activity Participant not found'], 404);
        }

        $activityParticipant->update($request->validated());
        $activityParticipant->load($this->relations);

        return response()->json($activityParticipant);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $activityParticipant = ActivityParticipant::find($id);

        if (!$activityParticipant) {
            return response()->json(['message' => 'Activity Participant not found'], 404);
        }

        $activityParticipant->delete();
        return response()->json(['message' => 'Activity Participant deleted successfully'], 200);
    }

    /**
     * Import participants from Excel file.
     */
    public function import(Request $request, $activityId)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:2048',
        ]);

        $import = new ActivityParticipantsImport((int) $activityId);
        Excel::import($import, $request->file('file'));

        return response()->json([
            'message' => 'Import selesai.',
            'imported' => $import->getImported(),
            'errors' => $import->getErrors(),
        ]);
    }

    /**
     * Download Excel template for participant import.
     */
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header row
        $sheet->setCellValue('A1', 'NIP');
        $sheet->setCellValue('B1', 'Nama');

        // Example rows
        $sheet->setCellValue('A2', 'EMP001');
        $sheet->setCellValue('B2', 'Contoh Nama Peserta');

        $writer = new Xlsx($spreadsheet);

        $filename = 'template_import_peserta.xlsx';
        $tempPath = storage_path('app/temp/' . $filename);

        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $writer->save($tempPath);

        return response()->download($tempPath, $filename)->deleteFileAfterSend(true);
    }
}
