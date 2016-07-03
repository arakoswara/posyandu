<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * AUTH
 */
use App\User;
use Auth;

class PeriksaBalitaController extends Controller
{
	public function __construct()
	{
	    $this->middleware('auth');

	    $this->middleware('role:visitor');

	    $user = User::findOrFail(Auth::user()->id);

	    view()->share('user', $user);
	}

    public function periksaBalita()
    {
        return view('visitor.periksa_balita.index');
    }
    
}
