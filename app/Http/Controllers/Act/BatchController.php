<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Models\Act\Batch;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Batch::query();
        if ($request->has('q') && $request->q != '') {
            $query->where('name', 'like', '%'.$request->q.'%');
        }
        $perPage = $request->input('entries', $request->input('per_page', 10));
        $batches = $query->paginate($perPage)->appends($request->all());

        return view('batch.index', compact('batches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('batch.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Batch::create($request->all());

        return redirect()->route('batches.index')->with('success', 'Batch berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return redirect()->route('batches.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $batch = Batch::findOrFail($id);

        return view('batch.edit', compact('batch'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $batch = Batch::findOrFail($id);
        $batch->update($request->all());

        return redirect()->route('batches.index')->with('success', 'Batch berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $batch = Batch::findOrFail($id);
        $batch->delete();

        return redirect()->route('batches.index')->with('success', 'Batch berhasil dihapus.');
    }
}
