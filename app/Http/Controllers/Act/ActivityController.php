<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Http\Requests\Act\StoreActivityRequest;
use App\Http\Requests\Act\UpdateActivityRequest;
use App\Models\Act\Activity;
use App\Models\Act\ActivityCategory;
use App\Models\Act\ActivityFormat;
use App\Models\Act\ActivityMethod;
use App\Models\Act\ActivityName;
use App\Models\Act\ActivityScope;
use App\Models\Act\ActivityType;
use App\Models\Act\Batch;
use App\Models\Act\MaterialType;
use App\Models\Act\FundSource;
use App\Models\Act\TargetParticipant;
use App\Models\Budget;
use App\Models\User\Profession;
use App\Models\User\User;
use App\Models\User\WorkUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // For Usulan Pengajuan Index
        $dataKegiatan = Activity::paginate(10);

        return view('usulan.pengajuan.index', compact('dataKegiatan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $picCandidates = User::all();
        $activity_names = ActivityName::whereDoesntHave('activities')->get();
        $activity_categories = ActivityCategory::all();
        $activity_types = ActivityType::all();
        $activity_scopes = ActivityScope::all();
        $material_types = MaterialType::all();
        $activity_methods = ActivityMethod::all();
        $batches = Batch::all();
        $activity_formats = ActivityFormat::all();
        $target_participants = TargetParticipant::all();
        $work_units = WorkUnit::all();
        $budgets = Budget::all();
        $fund_sources = FundSource::all();

        return view('usulan.pengajuan.create', compact(
            'picCandidates',
            'activity_names',
            'activity_categories',
            'activity_types',
            'activity_scopes',
            'material_types',
            'activity_methods',
            'batches',
            'activity_formats',
            'target_participants',
            'work_units',
            'budgets',
            'fund_sources'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreActivityRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;

        Activity::create($data);

        return redirect()->route('usulan-diklat')->with('success', 'Kegiatan berhasil diajukan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $searchPeserta = $request->input('search_peserta');

        $kegiatan = Activity::with([
            'activityName',
            'activityType',
            'activityScope',
            'materialType',
            'activityMethod',
            'batch',
            'activityFormat',
            'targetParticipant',
            'workUnit',
            'picUser',
            'latestStatus',
            'fundSource',
            'activityMaterials.speakers.user',
            'activityMaterials.moderators.user',
            'activityProfessions.profession',
            'activityParticipants' => function ($query) use ($searchPeserta) {
                if ($searchPeserta) {
                    $query->whereHas('user', function ($q) use ($searchPeserta) {
                        $q->where('name', 'like', '%'.$searchPeserta.'%')
                            ->orWhere('nip', 'like', '%'.$searchPeserta.'%');
                    });
                }
            },
            'activityParticipants.score',
            'activityParticipants.user.workUnit',
            'activityTargets',
        ])->findOrFail($id);

        $professions = Profession::all();
        $users = User::all();

        return view('usulan.detail.index', compact('kegiatan', 'professions', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kegiatan = Activity::findOrFail($id);

        $picCandidates = User::all();
        $activity_names = ActivityName::all();
        $activity_categories = ActivityCategory::all();
        $activity_types = ActivityType::all();
        $activity_scopes = ActivityScope::all();
        $material_types = MaterialType::all();
        $activity_methods = ActivityMethod::all();
        $batches = Batch::all();
        $activity_formats = ActivityFormat::all();
        $target_participants = TargetParticipant::all();
        $work_units = WorkUnit::all();
        $budgets = Budget::all();
        $fund_sources = FundSource::all();

        return view('usulan.pengajuan.edit', compact(
            'kegiatan',
            'picCandidates',
            'activity_names',
            'activity_categories',
            'activity_types',
            'activity_scopes',
            'material_types',
            'activity_methods',
            'batches',
            'activity_formats',
            'target_participants',
            'work_units',
            'budgets',
            'fund_sources'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActivityRequest $request, $id)
    {
        $activity = Activity::findOrFail($id);
        $activity->update($request->validated());

        return redirect()->route('usulan-diklat')->with('success', 'Kegiatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);
        $activity->delete();

        return redirect()->route('usulan-diklat')->with('success', 'Kegiatan berhasil dihapus.');
    }
}
