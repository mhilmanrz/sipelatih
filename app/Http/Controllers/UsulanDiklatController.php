<?php

namespace App\Http\Controllers;

use App\Models\Act\Activity;
use Illuminate\Http\Request;

class UsulanDiklatController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('entries', 10);
        $search = $request->input('search');

        $query = Activity::with(['activityName', 'activityType', 'materialType', 'latestStatus', 'workUnit', 'activityMaterials']);

        if ($search) {
            $query->whereHas('activityName', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })
                ->orWhereHas('workUnit', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
        }

        $kegiatan = $query->paginate($perPage);

        return view('usulan.usulan', compact('kegiatan'));
    }
}
