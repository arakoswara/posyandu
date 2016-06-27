<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function() {
	return view('home.index');
});

Route::group(['prefix' => 'auth'], function()
{	
	/**
	 * Handle form registration
	 */
	Route::get('/', [

		'as' => 'register-form', 'uses' => 'HomeController@register'
		
	]);

	/**
	 * Handle proses registration
	 */
	Route::post('register', [

		'as' => 'do-Register', 'uses' => 'UserController@doRegister'
	]);

	/**
	 * Handle activation account
	 */
	Route::get('active/{code}',[

		'as' => 'active-akun', 'uses' => 'UserController@doActive'
	]);

	/**
	 * Handle form login
	 */
	Route::get('login', [

		'as' => 'login-form', 'uses' => 'HomeController@login'
		
	]);

	Route::post('do_login', [

		'as' => 'do-Login', 'uses' => 'UserController@doLogin'
	]);

	Route::get('logout', [

		'as' => 'do-Logout', 'uses' => 'UserController@doLogout'

	]);
});

/**
 * ADMIN
 * =======================================================
 */
Route::get('/admin', [

	'as' => 'admin-index', 'uses' => 'AdminController@index'
]);

/**
 * VISITOR
 */

Route::group(['prefix' => 'sispos'], function() {

	Route::get('/', [

		'as' => 'data-balita', 'uses' => 'BalitaController@dashboard'
	]);

	Route::get('/data-balita', [

		'as' => 'data-balita', 'uses' => 'BalitaController@index'
	]);

	Route::post('/do-tambah', [

		'as' => 'do_tambah', 'uses' => 'BalitaController@doTambah'
	]);

	Route::get('/detail-balita/{id}', [

		'as' => 'detail_balita',

		'uses' => 'BalitaController@detailBalita'
	]);

	Route::get('/ubah/{id}', [

		'as' => 'ubah_balita',

		'uses' => 'BalitaController@ubahBalita'
	]);

	Route::post('/do-ubah-balita/{id}', [

		'as' => 'do_ubah_balita',

		'uses' => 'BalitaController@doUbahBalita'
	]);

	Route::get('/destroy-balita/{id}', [

		'as' => 'destroy_balita',

		'uses' => 'BalitaController@destroyBalita'
	]);

	/**
	 * PERIKSA
	 */
	Route::post('/do-periksa-balita', [

		'as' => 'do_periksa_balita',

		'uses' => 'BalitaController@doPeriksaBalita'
	]);

	Route::get('hitung-balita', [

		'use' => 'hitung-balita',

		'uses' => 'BalitaController@hitungBalita'
	]);

	Route::get('zbbu-gizi-buruk',[

		'as' => 'zbbu_gizi_buruk',

		'uses' => 'BalitaController@zbbuGiziburuk'
	]);

	Route::get('zbbu-gizi-kurang',[

		'as' => 'zbbu_gizi_kurang',

		'uses' => 'BalitaController@zbbuGizikurang'
	]);

	Route::get('zbbu-gizi-baik',[

		'as' => 'zbbu_gizi_baik',

		'uses' => 'BalitaController@zbbuGizibaik'
	]);

	Route::get('zbbu-gizi-lebih',[

		'as' => 'zbbu_gizi_lebih',

		'uses' => 'BalitaController@zbbuGizilebih'
	]);

	/**
	 * ZTBU
	 */

	Route::get('ztbu-sangat-pendek',[

		'as' => 'ztbu_sangat_pendek',

		'uses' => 'BalitaController@ztbuSangatPendek'
	]);

	Route::get('ztbu-pendek',[

		'as' => 'ztbu_pendek',

		'uses' => 'BalitaController@ztbuPendek'
	]);

	Route::get('ztbu-normal',[

		'as' => 'ztbu_normal',

		'uses' => 'BalitaController@ztbuNormal'
	]);

	Route::get('ztbu-tinggi',[

		'as' => 'ztbu_tinggi',

		'uses' => 'BalitaController@ztbuTinggi'
	]);

	/**
	 * ZBBTB
	 */
	Route::get('zbbtb-sangat-kurus',[

		'as' => 'zbbtb_sangat_kurus',

		'uses' => 'BalitaController@zbbtbSangatKurus'
	]);

	Route::get('zbbtb-kurus',[

		'as' => 'zbbtb_kurus',

		'uses' => 'BalitaController@zbbtbKurus'
	]);


	Route::get('zbbtb-normal',[

		'as' => 'zbbtb_normal',

		'uses' => 'BalitaController@zbbtbNormal'
	]);

	Route::get('zbbtb-gemuk',[

		'as' => 'zbbtb_gemuk',

		'uses' => 'BalitaController@zbbtbGemuk'
	]);

});
