<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataBalita;
use App\Score;
use App\Periksa;
use DB;

class ParentController extends Controller
{

    public function doPencarian(Request $request)
    {

        $no_reg = $request->all();

        $data_balita = DataBalita::where('no_reg', $no_reg['no_reg'])->first();

        $id = $data_balita['id'];

        $data_pencarian = Score::with('dataBalita')->with('periksa')->where('id_balita', $id)->orderBy('id', 'DESC')->first();

        // $grafik_score = Score::orderBy('id', 'ASC')->where('id_balita', $id)->get();

        $data = DB::table('score')

        				->join('periksa', 'periksa.id', '=', 'score.id_periksa')

        				->where('score.id_balita', $id)

        				->orderBy('score.id', 'ASC')

        				->select('score.*', 'periksa.berat_badan', 'periksa.tinggi_badan')

        				->get();

        $grafik_score = json_encode($data);

        return view('home.parent.index', compact('data_pencarian', 'data_balita', 'grafik_score'));

    }

    public function tampilkanSemuaRiwayat($id)
    {
    	return $data_riwayat = Score::with('dataBalita')->with('periksa')->where('id_balita', $id)->orderBy('id', 'DESC')->get();
    }
    
}
