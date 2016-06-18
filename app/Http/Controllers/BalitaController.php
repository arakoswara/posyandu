<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * Requests
 */
use App\Http\Requests\BalitaRequest;
use App\Http\Requests\PeriksaBalitaRequest;

/**
 * Models
 */
use App\DataBalita;

class BalitaController extends Controller
{
    public function index()
    {
        $data_balita = DataBalita::get();

        return view('visitor.balita.index', compact('data_balita'));
    }

    public function doTambah(BalitaRequest $request)
    {
        $input = $request->all();

        DataBalita::create($input);

        return redirect()->route('data_balita');
    }

    public function detailBalita($id)
    {

        $data_balita = DataBalita::findOrFail($id);

        return view('visitor.balita.detail', compact('data_balita'));
    }

    public function ubahBalita($id)
    {

        $data_balita = DataBalita::findOrFail($id);

        return view('visitor.balita.ubah', compact('data_balita'));
        
    }

    public function doUbahBalita(BalitaRequest $request, $id)
    {
        $data_balita = $request->all();

        $data_balita_db = DataBalita::findOrFail($id);

        $data_balita_db->update($data_balita);

        return redirect()->route('data_balita');
    }

    public function destroyBalita($id)
    {
        $data_balita = DataBalita::findOrFail($id);

        $data_balita->delete();

        return redirect()->route('data_balita');
    }

    /**
     * PERIKSA
     */
    public function doPeriksaBalita(PeriksaBalitaRequest $request)
    {
        return $request->all();
    }

}
