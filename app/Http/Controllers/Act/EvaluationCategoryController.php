<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Models\Act\EvaluationCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EvaluationCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $query = EvaluationCategory::query();

        if ($request->has('evaluation_type') && in_array($request->evaluation_type, [1, 3])) {
            $query->where('evaluation_type', $request->evaluation_type);
        }

        if ($request->has('q') && $request->q != '') {
            $query->where('name', 'like', '%'.$request->q.'%');
        }

        $categories = $query->orderBy('evaluation_type')->orderBy('order')->paginate(10)->appends($request->all());

        return view('evaluation_categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('evaluation_categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'evaluation_type' => ['required', 'integer', 'in:1,3'],
            'form_type' => ['nullable', 'string', 'in:speaker,activity'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        EvaluationCategory::create([
            'name' => $request->name,
            'evaluation_type' => $request->evaluation_type,
            'form_type' => $request->form_type,
            'order' => $request->order ?? 0,
        ]);

        return redirect()->route('evaluation-categories.index')
            ->with('success', 'Kategori evaluasi berhasil dibuat.');
    }

    public function edit(EvaluationCategory $evaluationCategory): View
    {
        return view('evaluation_categories.edit', compact('evaluationCategory'));
    }

    public function update(Request $request, EvaluationCategory $evaluationCategory): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'evaluation_type' => ['required', 'integer', 'in:1,3'],
            'form_type' => ['nullable', 'string', 'in:speaker,activity'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        $evaluationCategory->update([
            'name' => $request->name,
            'evaluation_type' => $request->evaluation_type,
            'form_type' => $request->form_type,
            'order' => $request->order ?? 0,
        ]);

        return redirect()->route('evaluation-categories.index')
            ->with('success', 'Kategori evaluasi berhasil diperbarui.');
    }

    public function destroy(EvaluationCategory $evaluationCategory): RedirectResponse
    {
        $evaluationCategory->delete();

        return redirect()->route('evaluation-categories.index')
            ->with('success', 'Kategori evaluasi berhasil dihapus.');
    }
}
