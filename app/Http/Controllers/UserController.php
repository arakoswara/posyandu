<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\AdminLoginRequest;

use App\Http\Controllers\Controller;

use App\User;
use Auth;

class UserController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('guest');
    // }

    /**
     * Handle proses registration
     */
    public function doRegister(RegisterRequest $request)
    {

    	$input = $request->all();

    	$password = bcrypt($request->input('password'));

    	$input['password'] = $password;

    	$input['activation_code'] = str_random(60).$request->input('email');

    	$register = User::create($input)->assignRole(2);

    	$data = [
    		'name' => $input['name'],
    		'code' => $input['activation_code']
    	];

    	return view('emails.email-activation', compact('data'));

    }

    /**
     * Handle activation account
     */
    public function doActive($code, User $user)
    {
        if ($user->activateAccount($code)) {
            
            return redirect()->route('login-form');

        }
        return "Fail";
    }

    /**
     * Handle proses login
     */
    public function doLogin(AdminLoginRequest $request)
    {
        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password')        
        ];

        if (Auth::attempt($credentials)) {
            
            // ceck aktifasi akun user
            if (Auth::user()->active == 0) {

                Auth::logout();
                
                return redirect()->route('login-form');

            } else {
                // cek role user sebagai admin
                if (Auth::user()->hasRole('admin')) {

                    session()->flash('message', 'selamat datang admin');
                    
                    return redirect()->route('admin-index');

                //cek role user sebagai visitor
                } else if (Auth::user()->hasRole('visitor')) {

                    // return "VISITOR";
                    return redirect()->route('visitor-index');

                }
            }
        } else {
            // user belum melakukan registrasi
            return redirect()->route('register-form');
        }

    }

    /**
     * handle Logout proses
     */
    public function doLogout()
    {
        Auth::logout();

        return redirect()->route('register-form');
    }
}