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

Route::get('/', [

	'as' => 'home_index',

	'uses' => 'HomeController@index'
]);

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

Route::get('/tambah-petugas', [

	'as' => 'tambah_petugas', 

	'uses' => 'AdminController@tambahPetugas'
]);

/**
 * VISITOR
 */

Route::group(['prefix' => 'sispos'], function() {

	Route::get('/', [

		'as' => 'dashboard', 'uses' => 'BalitaController@dashboard'
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

	/**
	 * R 1
	 */
	
	Route::get('/r-1', [

		'as' => 'r_1',

		'uses' => 'BalitaController@R_1'
	]);
	
	/**
	 * R 2
	 */
	
	Route::get('/r-2', [

		'as' => 'r_2',

		'uses' => 'BalitaController@R_2'
	]);


	/**
	 * R 3
	 */
	
	Route::get('/r-3', [

		'as' => 'r_3',

		'uses' => 'BalitaController@R_3'
	]);

	/**
	 * R 4
	 */
	
	Route::get('/r-4', [

		'as' => 'r_4',

		'uses' => 'BalitaController@R_4'
	]);

	/**
	 * R 5
	 */
	
	Route::get('/r-5', [

		'as' => 'r_5',

		'uses' => 'BalitaController@R_5'
	]);

	/**
	 * R 6
	 */
	
	Route::get('/r-6', [

		'as' => 'r_6',

		'uses' => 'BalitaController@R_6'
	]);

	/**
	 * R 7
	 */
	
	Route::get('/r-7', [

		'as' => 'r_7',

		'uses' => 'BalitaController@R_7'
	]);

	/**
	 * R 8
	 */
	
	Route::get('/r-8', [

		'as' => 'r_8',

		'uses' => 'BalitaController@R_8'
	]);

	/**
	 * R 9
	 */
	
	Route::get('/r-9', [

		'as' => 'r_9',

		'uses' => 'BalitaController@R_9'
	]);

	/**
	 * R 10
	 */
	
	Route::get('/r-10', [

		'as' => 'r_10',

		'uses' => 'BalitaController@R_10'
	]);

	/**
	 * R 11
	 */
	
	Route::get('/r-11', [

		'as' => 'r_11',

		'uses' => 'BalitaController@R_11'
	]);

	/**
	 * R 12
	 */
	
	Route::get('/r-12', [

		'as' => 'r_12',

		'uses' => 'BalitaController@R_12'
	]);

	/**
	 * R 13
	 */
	
	Route::get('/r-13', [

		'as' => 'r_13',

		'uses' => 'BalitaController@R_13'
	]);

	/**
	 * R 14
	 */
	
	Route::get('/r-14', [

		'as' => 'r_14',

		'uses' => 'BalitaController@R_14'
	]);

	/**
	 * R 15
	 */
	
	Route::get('/r-15', [

		'as' => 'r_15',

		'uses' => 'BalitaController@R_15'
	]);

	/**
	 * R 16
	 */
	
	Route::get('/r-16', [

		'as' => 'r_16',

		'uses' => 'BalitaController@R_16'
	]);

	/**
	 * R 17
	 */
	
	Route::get('/r-17', [

		'as' => 'r_17',

		'uses' => 'BalitaController@R_17'
	]);


	/**
	 * R 18
	 */
	
	Route::get('/r-18', [

		'as' => 'r_18',

		'uses' => 'BalitaController@R_18'
	]);

	/**
	 * R 19
	 */
	
	Route::get('/r-19', [

		'as' => 'r_19',

		'uses' => 'BalitaController@R_19'
	]);


	Route::get('/cari-energi', [

		'as' => 'cari_energi',

		'uses' => 'BalitaController@CariEnergi'
	]);

	Route::get('/cari-protein', [

		'as' => 'cari_protein',

		'uses' => 'BalitaController@CariProtein'
	]);

	Route::get('/sum-r', [

		'as' => 'sum_r',

		'uses' => 'BalitaController@sum_R'
	]);
	/**
	 * Profil
	 */
	Route::get('/profil', [

		'as' => 'edit_profil',

		'uses' => 'ProfileController@index'
	]);

	Route::post('/do-edit-profil', [

		'as' => 'do_edit_profil',

		'uses' => 'ProfileController@doEditProfil'
	]);

	Route::post('/ganti-password-petugas', [

		'as' => 'ganti_password_petugas',

		'uses' => 'ProfileController@gantiPasswordPetugas'
	]);
	
});

/**
 * Route Orang Tua
 */

Route::group(['prefix' => 'parent'], function(){

	Route::post('/do-pencaian/{no_reg}', [

		'as' => 'do_pencarian',

		'uses' => 'ParentController@doPencarian'

	]);

	Route::get('/semua-riwayat/{id}', [

		'as' => 'tampilkan_semua_riwayat',

		'uses' => 'ParentController@tampilkanSemuaRiwayat'
	]);

	/**
	 * PERIKSA
	 */
	Route::post('/do-periksa-balita-ortu', [

		'as' => 'do_periksa_balita_ortu',

		'uses' => 'ParentController@doPeriksaBalita'
	]);

	Route::get('hitung-balita', [

		'use' => 'hitung-balita',

		'uses' => 'ParentController@hitungBalita'
	]);

	Route::get('get-pdf-pencarian-all/{id}', [

		'as' => 'get_pdf_pencarian_all',

		'uses' => 'ParentController@getPDFPencarianAll'

	]);

	Route::get('get-pdf-pencarian/{id}', [

		'as' => 'get_pdf_pencarian',

		'uses' => 'ParentController@getPDFPencarian'

	]);

	Route::get('/get-pdf-periksa', [

		'as' => 'get_pdf_periksa',

		'uses' => 'ParentController@getPDFPeriksa'
	]);

});
