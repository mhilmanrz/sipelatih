<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Http\Requests\Act\StoreEvaluationCriteriaRequest;
use App\Http\Requests\Act\UpdateEvaluationCriteriaRequest;
use App\Models\Act\EvaluationCriteria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EvaluationCriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = EvaluationCriteria::query();

        if ($request->has('q') && $request->q != '') {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->q.'%')
                    ->orWhere('code', 'like', '%'.$request->q.'%');
            });
        }

        if ($request->has('evaluation_type') && in_array($request->evaluation_type, [1, 2, 3])) {
            $query->where('evaluation_type', $request->evaluation_type);
        }

        $perPage = (int) $request->input('entries', $request->input('per_page', 10));
        $criteria = $query->orderBy('evaluation_type')
            ->orderBy('order')
            ->paginate($perPage)
            ->appends($request->all());

        return view('evaluation_criteria.index', compact('criteria'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('evaluation_criteria.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEvaluationCriteriaRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_fillable'] = $request->boolean('is_fillable');

        EvaluationCriteria::create($validated);

        return redirect()->route('evaluation-criteria.index')->with('success', 'Kriteria Evaluasi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): RedirectResponse
    {
        return redirect()->route('evaluation-criteria.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $criterion = EvaluationCriteria::findOrFail($id);

        return view('evaluation_criteria.edit', compact('criterion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEvaluationCriteriaRequest $request, $id): RedirectResponse
    {
        $criterion = EvaluationCriteria::findOrFail($id);
        $validated = $request->validated();
        $validated['is_fillable'] = $request->boolean('is_fillable');

        $criterion->update($validated);

        return redirect()->route('evaluation-criteria.index')->with('success', 'Kriteria Evaluasi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        $criterion = EvaluationCriteria::findOrFail($id);
        $criterion->delete();

        return redirect()->route('evaluation-criteria.index')->with('success', 'Kriteria Evaluasi berhasil dihapus.');
    }
}
