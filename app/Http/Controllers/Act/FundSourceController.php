<?php

namespace App\Http\Controllers\Act;

use App\Http\Controllers\Controller;
use App\Models\Act\FundSource;
use Illuminate\Http\Request;

class FundSourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = FundSource::query();
        if ($request->has('q') && $request->q != '') {
            $query->where('name', 'like', '%'.$request->q.'%');
        }
        $perPage = $request->input('entries', $request->input('per_page', 10));
        $fundSources = $query->paginate($perPage)->appends($request->all());

        return view('fund_source.index', compact('fundSources'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('fund_source.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:fund_sources,name',
        ]);

        FundSource::create($request->all());

        return redirect()->route('fund-sources.index')->with('success', 'Sumber Dana berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $fundSource = FundSource::findOrFail($id);

        return view('fund_source.edit', compact('fundSource'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $fundSource = FundSource::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:fund_sources,name,'.$id,
        ]);

        $fundSource->update($request->all());

        return redirect()->route('fund-sources.index')->with('success', 'Sumber Dana berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $fundSource = FundSource::findOrFail($id);
        $fundSource->delete();

        return redirect()->route('fund-sources.index')->with('success', 'Sumber Dana berhasil dihapus.');
    }
}
