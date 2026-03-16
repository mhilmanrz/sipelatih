<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreActivityKakFileRequest;
use App\Http\Requests\UpdateActivityKakFileRequest;
use App\Models\Act\ActivityKakFile;
use Illuminate\Support\Facades\Storage;

class ActivityKakFileController extends Controller
{
    /**
     * Download the KAK Word template.
     */
    public function downloadTemplate()
    {
        $templatePath = storage_path('app/templates/template_kak.docx');

        if (!file_exists($templatePath)) {
            return response()->json(['message' => 'Template file not found'], 404);
        }

        return response()->download($templatePath, 'Template_KAK.docx');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activityKakFiles = ActivityKakFile::paginate(10);
        return response()->json($activityKakFiles);
    }

    /**
     * Store a newly created resource in storage.
     * Receives a PDF file upload via multipart/form-data.
     */
    public function store(StoreActivityKakFileRequest $request)
    {
        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $filePath = $file->store('kak-files', 'public');

        $activityKakFile = ActivityKakFile::create([
            'activity_id' => $request->activity_id,
            'file_path' => $filePath,
            'original_name' => $originalName,
        ]);

        return response()->json($activityKakFile, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $activityKakFile = ActivityKakFile::find($id);

        if (!$activityKakFile) {
            return response()->json(['message' => 'Activity KAK File not found'], 404);
        }

        return response()->json($activityKakFile);
    }

    /**
     * Update the specified resource in storage.
     * Receives a new PDF file upload to replace the existing one.
     */
    public function update(UpdateActivityKakFileRequest $request, string $id)
    {
        $activityKakFile = ActivityKakFile::find($id);

        if (!$activityKakFile) {
            return response()->json(['message' => 'Activity KAK File not found'], 404);
        }

        // Delete old file from storage
        if (Storage::disk('public')->exists($activityKakFile->file_path)) {
            Storage::disk('public')->delete($activityKakFile->file_path);
        }

        // Store new file
        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $filePath = $file->store('kak-files', 'public');

        $activityKakFile->update([
            'file_path' => $filePath,
            'original_name' => $originalName,
        ]);

        return response()->json($activityKakFile);
    }

    /**
     * Download the uploaded KAK file.
     */
    public function download(string $id)
    {
        $activityKakFile = ActivityKakFile::find($id);

        if (!$activityKakFile) {
            return response()->json(['message' => 'Activity KAK File not found'], 404);
        }

        $fullPath = Storage::disk('public')->path($activityKakFile->file_path);

        if (!file_exists($fullPath)) {
            return response()->json(['message' => 'File not found in storage'], 404);
        }

        return response()->download($fullPath, $activityKakFile->original_name);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $activityKakFile = ActivityKakFile::find($id);

        if (!$activityKakFile) {
            return response()->json(['message' => 'Activity KAK File not found'], 404);
        }

        // Delete file from storage
        if (Storage::disk('public')->exists($activityKakFile->file_path)) {
            Storage::disk('public')->delete($activityKakFile->file_path);
        }

        $activityKakFile->delete();
        return response()->json(['message' => 'Activity KAK File deleted successfully'], 200);
    }
}
