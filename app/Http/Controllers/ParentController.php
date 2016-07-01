<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataBalita;
use App\Score;
use App\Periksa;

class ParentController extends Controller
{

    public function doPencarian(Request $request)
    {

        $no_reg = $request->all();

        $data_balita = DataBalita::where('no_reg', $no_reg['no_reg'])->first();

        $id = $data_balita['id'];

        $data_pencarian = Score::with('dataBalita')->with('periksa')->where('id_balita', $id)->first();

        $grafik_score = Score::orderBy('id', 'ASC')->where('id_balita', $id)->get();

        return view('home.parent.index', compact('data_pencarian', 'data_balita', 'grafik_score'));

    }
    
}
