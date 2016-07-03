<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\ChangeProfileRequest;

/**
 * AUTH
 */
use App\User;
use Auth;

class ProfileController extends Controller
{
	public function __construct()
	{
	    $this->middleware('auth');

	    $this->middleware('role:visitor');

	    $user = User::findOrFail(Auth::user()->id);

	    view()->share('user', $user);
	}

    public function index()
    {
    	return view('visitor.profil.index');
    }

    public function doEditProfil(ChangeProfileRequest $request)
    {
    	return $request->all();
    }
}
