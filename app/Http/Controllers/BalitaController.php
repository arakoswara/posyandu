<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * AUTH
 */
use App\User;
use Auth;

/**
 * Requests
 */
use App\Http\Requests\BalitaRequest;
use App\Http\Requests\PeriksaBalitaRequest;

/**
 * Models
 */
use App\DataBalita;
use App\Periksa;
use App\BBU;
use App\TBU;
use App\BBTB1;
use App\BBTB2;
use App\Score;

class BalitaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('role:visitor');
    }

    public function dashboard()
    {

        return view('visitor.index');

    }
    /**
     * [index] menampilkan data pada halaman index
     */
    public function index()
    {
        /**
         * mengambil data balita melalui model DataBalita 
         * dari table data_balita
         */
        $data_balita = DataBalita::get();

        return view('visitor.balita.index', compact('data_balita'));
    }

    /**
     * [doTambah] proses untuk mela
     */
    public function doTambah(BalitaRequest $request)
    {
        /**
         * Variabel temporary dari inputan form
         */
        $input = $request->all();

        /**
         * Insert data kedalam table data_balita melalui model
         */
        DataBalita::create($input);

        return redirect()->route('data_balita');
    }

    /**
     * [detailBalita]
     * Menampilkan detail dari balita
     */
    public function detailBalita($id)
    {
        /**
         * Mengambil data balita dengan parameter id
         */
        $data_balita = DataBalita::findOrFail($id);

        /**
         * mengambil data score dan semua relasinya
         */
        $score = Score::with('dataBalita')->with('periksa')->orderBy('id', 'DESC')->where('id_balita', $id)->first();
        
        $grafik_score = Score::with('dataBalita')->with('periksa')->orderBy('id', 'DESC')->where('id_balita', $id)->get();

        return view('visitor.balita.detail', compact('data_balita', 'score', 'grafik', 'grafik_score'));
    }

    /**
     * [ubahBalita description]
     * manampilkan data pda form update data balita
     */
    public function ubahBalita($id)
    {
        /**
         * [$data_balita description]
         * mengambil data balita yang akan diupdate
         * berdasarkan parameter id
         */
        $data_balita = DataBalita::findOrFail($id);

        return view('visitor.balita.ubah', compact('data_balita'));
        
    }

    /**
     * [doUbahBalita description]
     * proses update data balita
     */
    public function doUbahBalita(BalitaRequest $request, $id)
    {
        /**
         * [$data_balita description]
         * Temporary variabel dari form update
         */
        $data_balita = $request->all();

        /**
         * [$data_balita_db description]
         * ambil data dari table balita yang akan di update
         */
        $data_balita_db = DataBalita::findOrFail($id);

        /**
         * proses update data
         */
        $data_balita_db->update($data_balita);

        return redirect()->route('data_balita');
    }

    /**
     * [destroyBalita description]
     * menghapus data balita
     */
    public function destroyBalita($id)
    {  
        /**
         * [$data_balita description]
         * ambil data yang akan di update berdasarkan id balita
         */
        $data_balita = DataBalita::findOrFail($id);

        /**
         * proses delete
         */
        $data_balita->delete();

        return redirect()->route('data_balita');
    }

    /**
     * proses pemeriksaan
     */
    public function doPeriksaBalita(PeriksaBalitaRequest $request)
    {
        /**
         * [$input description]
         * temporary variabel dari form periksa
         */
        $input = $request->all();

        $id_balita = $input['id_balita'];

        Periksa::create($input);

        $this->hitungBalita($id_balita);

        return redirect()->back();
    }

    public function hitungBalita($id_balita)
    {
        /**
         * mengambil data dari table periksa dan balita berdasarkan reasi one to many
         */
        $periksa_balita = Periksa::with('dataBalita')->find($id_balita);

        /**
         * Hitung umur balita
         */
        $umur = (strtotime($periksa_balita->tgl_periksa) - strtotime($periksa_balita->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30);

        /**
         * pembulatan umur
         */
        $umur_bulat = floor($umur);

        $this->perhitunganScore($periksa_balita, $umur_bulat);

        // return "Oke";
    }

    /**
     * hitung ZBBU
     */
    public function perhitunganScore($periksa_balita, $umur_bulat)
    {
        $bbu = BBU::where('jk', $periksa_balita->dataBalita->jenis_kelamin)->where('umur', $umur_bulat)->first();

        if ($periksa_balita->berat_badan < $bbu['median']) {

            $nsbr = $bbu['median'] - $bbu['sdmin1'];

        }else{

            $nsbr = $bbu['sdplus1'] - $bbu['median'];

        }

        $zbbu = (($periksa_balita->berat_badan - $bbu['median'])/$nsbr);

        /**
         * Hitung TBU
         * ============================================================
         */    
        $tbu = TBU::where('jk', $periksa_balita->dataBalita->jenis_kelamin)->where('umur', $umur_bulat)->first();

        /**
         * cek apakah tinggi badan lebih kecil dari median
         */
        if ($periksa_balita->tinggi_badan < $tbu['median']) {
            
            $nsbr = $tbu['median'] - $tbu['sdmin1'];
            
        }else{

            $nsbr = $tbu['sdplus1'] - $tbu['median'];

        }

        $ztbu = (($periksa_balita->tinggi_badan - $tbu['median'])/$nsbr);

        /**
         * Hitung ZBBTB
         * =====================================================
         */
        /**
         * Cek umur balita apaka lebih kecil sama dengan 24 bulan
         */
        if ($umur_bulat <= 24) {

            /**
             * mengambil data dari table BBTB1
             */
            $bbtb_1 = BBTB1::where('jk', $periksa_balita->dataBalita->jenis_kelamin)->where('tb', $periksa_balita->tinggi_badan)->first();

            if ($periksa_balita->berat_badan < $bbtb_1['median']) {

                $nsbr = $bbtb_1['median'] - $bbtb_1['sdmin1'];

            }else{

                $nsbr = $bbtb_1['sdplus1'] - $bbtb_1['median'];

            }

            // return "oke";

            $zbbtb = (($periksa_balita->berat_badan - $bbtb_1['median'])/$nsbr);

        /**
         * Apabila umur balita diatas 24 bulan
         */
        }else {

            $bbtb_2 = BBTB2::where('jk', $periksa_balita->dataBalita->jenis_kelamin)->where('tb', $periksa_balita->tinggi_badan)->first();

            if ($periksa_balita->berat_badan < $bbtb_2['median']) {

                $nsbr = $bbtb_2['median'] - $bbtb_2['sdmin1'];

            }else{

                $nsbr = $bbtb_2['sdplus1'] - $bbtb_2['median'];

            }

            $zbbtb = (($periksa_balita->berat_badan - $bbtb_2['median'])/$nsbr);

        }

        /**
         * Insert Score
         */
        $input['id_balita'] = $periksa_balita->id_balita;

        $input['id_periksa']= $periksa_balita->id;

        $input['zbbu']      = $zbbtb;

        $input['ztbu']      = $zbbtb;

        $input['zbbtb']     = $zbbtb;

        Score::create($input);

        // if ($zbbtb < -3) {

        //     return "gizi buruk";

        // }elseif ($zbbtb > -3 && $zbbtb < -2) {
            
        //     return "gizi kurang";

        // }elseif ($zbbtb > -2 && $zbbtb < 2) {
            
        //     return "gizi baik";

        // } elseif ($zbbtb > 2) {
            
        //     return "gizi lebih";
            
        // }
    }

}
