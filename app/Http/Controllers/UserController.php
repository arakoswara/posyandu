<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\AdminLoginRequest;

use App\Http\Controllers\Controller;

use App\User;
use Auth;
use Sendinblue\Mailin;

class UserController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');

        // $this->middleware('role:guest');

        // $this->middleware('auth');

        // $this->middleware('role:visitor');

    }

    /**
     * Handle proses registration
     */
    public function doRegister(RegisterRequest $request)
    {

    	$input = $request->all();

        $email = $input['email'];

    	$password = bcrypt($request->input('password'));

    	$input['password'] = $password;

    	$input['activation_code'] = str_random(60).$request->input('email');

    	$register = User::create($input)->assignRole(2);

    	// $data = [
    	// 	'name' => $input['name'],
    	// 	'code' => $input['activation_code']
    	// ];
        $code = $input['activation_code']; 

    	// return view('emails.email-activation', compact('data'));
        $this->emailAktivasi($code, $email);

    }

    public function emailAktivasi($code, $email)
    {
        $mailin = new Mailin("https://api.sendinblue.com/v2.0","92qTN7E1xabfy0Ir");

            $data = array( "to" => array($email => $email),

                "from" => array("info.tukangketik@gmail.com", "INFO"),

                "subject" => "AKTIVASI AKUN PETUGAS",

                "html" => "Klik kode dibawah ini untuk mengaktifkan akun anda : <br>".
                "<a href='http://localhost/posyandu/public/auth/active/$code'>$code</a>"

            );
         
        $mailin->send_email($data);

        session()->flash('Akun Petugas baru berhasil ditambahkan');

        return redirect()->back();
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
                    return redirect()->route('data-balita');

                }
            }
        } else {
            
            session()->flash('message', 'Email dan Password belum terdaftar / tidak sesuai');

            return redirect()->route('login-form');
        }

    }

    /**
     * handle Logout proses
     */
    public function doLogout()
    {
        Auth::logout();

        return redirect()->route('home_index');
    }
}