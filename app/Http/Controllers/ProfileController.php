<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\ChangeProfileRequest;

class ProfileController extends Controller
{
    public function index()
    {
    	return view('visitor.profil.index');
    }

    public function doEditProfil(ChangeProfileRequest $request)
    {
    	return $request->all();
    }
}
