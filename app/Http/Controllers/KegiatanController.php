<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;

class KegiatanController extends Controller
{
    public function index()
    {
        $title = 'Kegiatan';

        $dataKegiatan = Kegiatan::get();

        return view('usulan.pengajuan.index', compact('title', 'dataKegiatan'));
    }
}



