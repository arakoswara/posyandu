<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PeriksaBalitaController extends Controller
{
    public function periksaBalita()
    {
        return view('visitor.periksa_balita.index');
    }
    
}
