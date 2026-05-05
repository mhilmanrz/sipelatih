<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Models\Act\Activity;
use App\Models\Act\ActivityReport;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class ActivityReportController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', date('Y'));

        $activities = Activity::with([
            'report', 'activityName', 'activityScope', 'activityCategory',
            'activityMaterials', 'activityParticipants', 'latestStatus',
        ])->where(function ($q) use ($year) {
            $q->whereYear('start_date', $year)
                ->orWhereYear('end_date', $year);
        })->get();

        // Paginate activities
        $perPage = $request->input('entries', 10);
        $page = $request->input('page', 1);
        $paginatedActivities = new LengthAwarePaginator(
            $activities->forPage($page, $perPage),
            $activities->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Available years for filter
        $availableYears = Activity::selectRaw('YEAR(start_date) as year')
            ->whereNotNull('start_date')
            ->union(Activity::selectRaw('YEAR(end_date) as year')->whereNotNull('end_date'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        if (empty($availableYears)) {
            $availableYears = [date('Y')];
        } elseif (! in_array(date('Y'), $availableYears)) {
            $availableYears[] = date('Y');
            rsort($availableYears);
        }

        // Fetch data for Pie Chart (tidak difilter tahun agar tetap akurat)
        $totalActivities = Activity::count();
        $statusCounts = [
            'draft' => Activity::whereDoesntHave('latestStatus')->count(),
            'submitted' => Activity::whereHas('latestStatus', function ($q) {
                $q->where('status', 'submitted');
            })->count(),
            'revision' => Activity::whereHas('latestStatus', function ($q) {
                $q->where('status', 'revision');
            })->count(),
            'accepted' => Activity::whereHas('latestStatus', function ($q) {
                $q->where('status', 'accepted');
            })->count(),
        ];

        return view('laporanKegiatan', compact('activities', 'paginatedActivities', 'totalActivities', 'statusCounts', 'year', 'availableYears'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        $file = $request->file('file');
        $filename = time().'_'.$file->getClientOriginalName();
        $path = $file->storeAs('reports', $filename, 'public');

        ActivityReport::create([
            'activity_id' => $request->activity_id,
            'file_path' => $path,
        ]);

        return back()->with('success', 'Laporan berhasil diupload.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        $report = ActivityReport::findOrFail($id);

        // Delete old file
        if (Storage::disk('public')->exists($report->file_path)) {
            Storage::disk('public')->delete($report->file_path);
        }

        $file = $request->file('file');
        $filename = time().'_'.$file->getClientOriginalName();
        $path = $file->storeAs('reports', $filename, 'public');

        $report->update([
            'file_path' => $path,
        ]);

        return back()->with('success', 'Laporan berhasil diperbarui.');
    }

    public function downloadTemplate()
    {
        $filename = 'Template_Laporan_Kegiatan.docx';
        $content = 'Ini adalah template laporan kegiatan dummy.';

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $filename);
    }
}
