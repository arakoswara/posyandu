<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\SKDNRequest;
use App\Http\Requests\PetugasRequest;
use App\User;
use App\Role;
use App\RoleUser;
use Auth;
use App\SKDN;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('role:admin');

        $role_user = RoleUser::where('user_id', Auth::user()->id)->first();

        view()->share('role_user', $role_user);

        $user = User::findOrFail(Auth::user()->id);

        view()->share('user', $user);

        $data_petugas = User::get();

        view()->share('data_petugas', $data_petugas);
    }

    public function index()
    {
        $user = User::findOrFail(Auth::user()->id);

        $data_petugas = User::get();

        return view('admin.index', compact('user', 'data_petugas'));
    }

    public function tambahSKDN()
    {
        $skdn = SKDN::get();

        return view('admin.skdn.index', compact('skdn'));
    }

    public function doTambahSKDN(SKDNRequest $request)
    {
        $input = $request->all();

        $month = date("d-M-Y",strtotime($input['date']));

        $input['date'] = $month;

        SKDN::create($input);

        session()->flash('message', 'Data SKDN berhasil ditambahkan');

        return redirect()->back();
    }

    public function doUpdatePetugas(PetugasRequest $request)
    {
        $input = $request->all();

        $data = User::findOrFail($input['id']);

        $data->update($input);

        return redirect()->back();

    }
}
