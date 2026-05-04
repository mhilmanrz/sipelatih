<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\Rank;
use Illuminate\Http\Request;

class RankController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Rank::query();

        if ($search) {
            $query->where('name', 'like', '%'.$search.'%')
                ->orWhere('code', 'like', '%'.$search.'%');
        }

        $ranks = $query->paginate(10);

        return view('rank.index', compact('ranks'));
    }

    public function create()
    {
        return view('rank.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:ranks,code',
            'name' => 'required|string|max:255',
        ]);

        Rank::create($validated);

        return redirect()->route('ranks.index')->with('success', 'Data Pangkat berhasil ditambahkan.');
    }

    public function show(Rank $rank)
    {
        return redirect()->route('ranks.index');
    }

    public function edit(Rank $rank)
    {
        return view('rank.edit', compact('rank'));
    }

    public function update(Request $request, Rank $rank)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:ranks,code,'.$rank->id,
            'name' => 'required|string|max:255',
        ]);

        $rank->update($validated);

        return redirect()->route('ranks.index')->with('success', 'Data Pangkat berhasil diperbarui.');
    }

    public function destroy(Rank $rank)
    {
        $rank->delete();

        return redirect()->route('ranks.index')->with('success', 'Data Pangkat berhasil dihapus.');
    }
}
