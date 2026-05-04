<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndikatorKinerjaController extends Controller
{
    public function index(Request $request)
    {

        return view('indikatorKinerja', compact(
            'year',
            'numerator1',
            'denominator1',
            'teiPercentage',
            'numerator2',
            'denominator2',
            'cgPercentage'
        ));
    }
}
