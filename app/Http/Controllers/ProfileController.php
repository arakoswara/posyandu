<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\ChangeProfileRequest;
use App\Http\Requests\ChangePasswordRequest;

/**
 * AUTH
 */
use App\User;
use Auth;
use App\RoleUser;

class ProfileController extends Controller
{
	public function __construct()
	{
	    $this->middleware('auth');

	    $this->middleware('role:visitor');

	    $user = User::findOrFail(Auth::user()->id);

	    view()->share('user', $user);

        $role_user = RoleUser::where('user_id', Auth::user()->id)->first();

        view()->share('role_user', $role_user);
	}

    public function index()
    {
    	return view('visitor.profil.index');
    }

    public function doEditProfil(ChangeProfileRequest $request)
    {
    	$input = $request->all();

        $user = User::findOrFail($input['id']);

        $user->update($input);

        session()->flash('message', 'Email & Nama anda berhasil diperbaharui.');

        return redirect()->back();
    }

    public function gantiPasswordPetugas(ChangePasswordRequest $request)
    {
        $input = $request->all();

        $credentials = [

            'email' => Auth::user()->email,

            'password' => $input['old_password']
        ];

        if (Auth::attempt($credentials)) {

            $new_password['password'] = bcrypt($input['password']);

            $user = User::where('email', Auth::user()->email)->first();

            $user->update($new_password);

            session()->flash('message','Password anda berhasil diperbaharui.');

            return redirect()->back();
        }else{

            session()->flash('message', 'Password lama anda salah');

            return redirect()->back();
        }

    }
}
