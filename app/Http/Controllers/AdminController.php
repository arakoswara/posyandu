<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Role;
use App\RoleUser;
use Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('role:admin');

        $role_user = RoleUser::where('user_id', Auth::user()->id)->first();

        view()->share('role_user', $role_user);
    }

    public function index()
    {
        $user = User::findOrFail(Auth::user()->id);

        return view('admin.index', compact('user'));
    }
}
