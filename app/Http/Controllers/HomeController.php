<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\AdminLoginRequest;

class HomeController extends Controller
{
	public function __construct()
    {
        $this->middleware('guest');
    }
    
    public function register()
    {
        return view('auth.register');
    }

    public function login()
    {
        return view('auth.login');
    }
}
