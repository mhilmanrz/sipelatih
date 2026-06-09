<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Act\Activity;
use Illuminate\Http\Request;

class MyActivityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        $activities = Activity::with(['activityName', 'latestStatus'])
            ->where(function ($query) use ($user) {
                $query->whereHas('participants', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                    ->orWhereHas('speakers', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })
                    ->orWhereHas('moderators', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    });
            })
            ->orderBy('start_date', 'desc')
            ->paginate($request->input('per_page', 10));

        return view('my-activities.index', compact('activities'));
    }

    public function show($id)
    {
        $user = auth()->user();

        $activity = Activity::with([
            'activityName',
            'latestStatus',
            'materials',
            'participants' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            },
            'speakers' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            },
            'moderators' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            },
        ])
            ->where(function ($query) use ($user) {
                $query->whereHas('participants', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                    ->orWhereHas('speakers', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })
                    ->orWhereHas('moderators', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    });
            })
            ->findOrFail($id);

        $isParticipant = $activity->participants->isNotEmpty();
        $isSpeaker = $activity->speakers->isNotEmpty();
        $isModerator = $activity->moderators->isNotEmpty();

        $participantData = $isParticipant ? $activity->participants->first() : null;

        return view('my-activities.show', compact('activity', 'isParticipant', 'isSpeaker', 'isModerator', 'participantData'));
    }
}
