<?php

namespace App\Http\Controllers;

use App\Models\Act\EvaluationCriteria;
use App\Models\Act\ParticipantEvaluation;
use App\Models\Act\ParticipantEvaluationFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PublicEvaluationController extends Controller
{
    public function show($token): View
    {
        $evaluation = ParticipantEvaluation::where('token', $token)
            ->with([
                'participant.activity.activityName',
                'participant.user',
                'speaker.user',
                'answers.criteria',
                'files',
            ])
            ->firstOrFail();

        $criteria = EvaluationCriteria::with('category')
            ->select('evaluation_criteria.*')
            ->leftJoin('evaluation_categories', 'evaluation_criteria.evaluation_category_id', '=', 'evaluation_categories.id')
            ->where('evaluation_criteria.evaluation_type', $evaluation->evaluation_type)
            ->where(function ($query) use ($evaluation) {
                if ($evaluation->form_type) {
                    $query->where('evaluation_criteria.form_type', $evaluation->form_type);
                } else {
                    $query->whereNull('evaluation_criteria.form_type');
                }
            })
            ->orderByRaw('COALESCE(evaluation_categories.order, 9999)')
            ->orderBy('evaluation_criteria.order')
            ->get()
            ->groupBy('evaluation_category_id');

        return view('public.evaluation-form', compact('evaluation', 'criteria'));
    }

    public function store(Request $request, $token): RedirectResponse
    {
        $evaluation = ParticipantEvaluation::where('token', $token)->firstOrFail();

        if ($evaluation->isSubmitted()) {
            return redirect()->back()->withErrors(['error' => 'Evaluasi sudah disubmit sebelumnya.']);
        }

        $allCriteriaIds = array_merge(
            array_keys($request->input('answers', [])),
            array_keys($request->allFiles()['files'] ?? [])
        );
        $criteria = EvaluationCriteria::whereIn('id', $allCriteriaIds)->get();

        $rules = [
            'answers' => ['nullable', 'array'],
            'files' => ['nullable', 'array'],
            'supervisor_recommendation' => ['nullable', 'string', 'max:1000'],
        ];

        foreach ($criteria as $crit) {
            if ($crit->type === 'rating') {
                $rules["answers.{$crit->id}"] = ['required', 'integer', 'between:1,4'];
            } elseif ($crit->type === 'file') {
                $rules["files.{$crit->id}"] = ['nullable', 'file', 'max:10240'];
            } else {
                $rules["answers.{$crit->id}"] = ['nullable', 'string', 'max:2000'];
            }
        }

        $request->validate($rules);

        $ipAddress = $request->ip();

        DB::transaction(function () use ($evaluation, $request, $ipAddress, $criteria) {
            foreach ($request->input('answers', []) as $criteriaId => $answer) {
                $crit = $criteria->firstWhere('id', $criteriaId);
                if (! $crit) {
                    continue;
                }

                $data = $crit->type === 'rating'
                    ? ['rating' => $answer, 'answer_text' => null]
                    : ['rating' => null, 'answer_text' => $answer];

                $evaluation->answers()->updateOrCreate(
                    ['evaluation_criteria_id' => $criteriaId],
                    $data
                );
            }

            foreach ($request->allFiles()['files'] ?? [] as $criteriaId => $file) {
                $crit = $criteria->firstWhere('id', $criteriaId);
                if (! $crit || $crit->type !== 'file') {
                    continue;
                }

                $path = $file->store('evaluation-files', 'local');

                ParticipantEvaluationFile::updateOrCreate(
                    [
                        'participant_evaluation_id' => $evaluation->id,
                        'evaluation_criteria_id' => $criteriaId,
                    ],
                    [
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                    ]
                );
            }

            $evaluation->update([
                'supervisor_recommendation' => $request->input('supervisor_recommendation'),
                'submitted_at' => now(),
                'ip_address' => $ipAddress,
            ]);
        });

        return view('public.evaluation-success', compact('evaluation'));
    }
}
