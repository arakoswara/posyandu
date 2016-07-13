<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataBalita;
use App\Http\Requests\PencarianRequest;
use App\Http\Requests\PeriksaBalitaParentRequest;
use App\Score;
use App\Periksa;
use DB;
use App\BBU;
use App\TBU;
use App\BBTB1;
use App\BBTB2;
use PDF;
use App;

class ParentController extends Controller
{

    public function doPencarian(PencarianRequest $request)
    {

        $no_reg = $request->all();

        $data_balita = DataBalita::where('no_reg', $no_reg['no_reg'])->first();

        $id = $data_balita['id'];

        $data_pencarian = Score::with('dataBalita')->with('periksa')->where('id_balita', $id)->orderBy('id', 'DESC')->first();

        // $grafik_score = Score::orderBy('id', 'ASC')->where('id_balita', $id)->get();

        $data = DB::table('score')

        				->join('periksa', 'periksa.id', '=', 'score.id_periksa')

        				->where('score.id_balita', $id)

        				->orderBy('score.id', 'ASC')

        				->select('score.*', 'periksa.berat_badan', 'periksa.tinggi_badan')

        				->get();

        $grafik_score = json_encode($data);

        return view('home.parent.index', compact('data_pencarian', 'data_balita', 'grafik_score'));

    }

    public function tampilkanSemuaRiwayat($id)
    {
        $data_balita = DataBalita::where('id', $id)->first();

    	$data_riwayat = Score::with('dataBalita')->with('periksa')->where('id_balita', $id)->orderBy('id', 'DESC')->paginate(2);

        return view('home.parent.semua-riwayat', compact('data_riwayat', 'data_balita'));
    }

    /**
     * proses pemeriksaan
     */
    public function doPeriksaBalita(PeriksaBalitaParentRequest $request)
    {
        /**
         * temporary variabel dari form periksa
         */
        $input = $request->all();

        $date = date_create($input['tgl_periksa']);

        $input['tgl_periksa'] = date_format($date, 'd-m-Y');

        $time=strtotime($input['tgl_periksa']);

        $month=date("F",$time);

        $data_balita = DataBalita::where('no_reg', $input['no_reg'])->first();

        /**
         * Hitung umur balita
         */
        $umur = (strtotime($input['tgl_periksa']) - strtotime($data_balita->tgl_lahir)) / (60 * 60 * 24 * 30);

        /**
         * pembulatan umur
         */
        $umur_bulat = floor($umur);

        /**
         * HITUNG BBU
         */
        $bbu = BBU::where('jk', $data_balita->jenis_kelamin)->where('umur', $umur_bulat)->first();

        if ($input['berat_badan'] < $bbu['median']) {

            $nsbr = $bbu['median'] - $bbu['sdmin1'];

        }else{

            $nsbr = $bbu['sdplus1'] - $bbu['median'];

        }

        $zbbu = (($input['berat_badan'] - $bbu['median'])/$nsbr);

        /**
         * Hitung TBU
         * ============================================================
         */    
        $tbu = TBU::where('jk', $data_balita->jenis_kelamin)->where('umur', $umur_bulat)->first();

        /**
         * cek apakah tinggi badan lebih kecil dari median
         */
        if ($input['tinggi_badan'] < $tbu['median']) {
            
            $nsbr = $tbu['median'] - $tbu['sdmin1'];
            
        }else{

            $nsbr = $tbu['sdplus1'] - $tbu['median'];

        }

        $ztbu = (($input['tinggi_badan'] - $tbu['median'])/$nsbr);

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
            return $bbtb_1 = BBTB1::where('jk', $data_balita->jenis_kelamin)->where('tb', $input['tinggi_badan'])->first();

            if ($input['berat_badan'] < $bbtb_1['median']) {

                $nsbr = $bbtb_1['median'] - $bbtb_1['sdmin1'];

            }else{

                $nsbr = $bbtb_1['sdplus1'] - $bbtb_1['median'];

            }

            // return "oke";

            $zbbtb = (($input['berat_badan'] - $bbtb_1['median'])/$nsbr);

        /**
         * Apabila umur balita diatas 24 bulan
         */
        }else {

            $bbtb_2 = BBTB2::where('jk', $data_balita->jenis_kelamin)->where('tb', $input['tinggi_badan'])->first();

            if ($input['berat_badan'] < $bbtb_2['median']) {

                $nsbr = $bbtb_2['median'] - $bbtb_2['sdmin1'];

            }else{

                $nsbr = $bbtb_2['sdplus1'] - $bbtb_2['median'];

            }

            $zbbtb = (($input['berat_badan'] - $bbtb_2['median'])/$nsbr);

        }

        /**
         * Insert Score
         */        
        $data_periksa['month']     = $month;

        $data_periksa['id_balita'] = $data_balita->id;

        $data_periksa['zbbu']      = $zbbu;

        $data_periksa['ztbu']      = $ztbu;

        $data_periksa['zbbtb']     = $zbbtb;

        $data_periksa['tinggi_badan'] = $input['tinggi_badan'];

        $data_periksa['berat_badan'] = $input['berat_badan'];

        $data_periksa['tgl_periksa'] = $input['tgl_periksa'];

        return $data_periksa;

        // return $this->getPDFPeriksa($data_periksa, $data_balita);

        return view('home.parent.hasil-periksa', compact('data_periksa', 'data_balita'));
    }

    public function getPDFPencarianAll($id)
    {
        $data = Score::with('dataBalita')->with('periksa')->where('id_balita', $id)->orderBy('id', 'DESC')->get();

        $data_balita = DataBalita::where('id', $id)->first();

        $pdf = PDF::loadView('home.parent.pdf-all', compact('data', 'data_balita'));

        return $pdf->stream('semua-riwayat.pdf');  
    }

    public function getPDFPencarian($id)
    {
        $data = Score::with('dataBalita')->with('periksa')->where('id_balita', $id)->orderBy('id', 'DESC')->first();

        $data_balita = DataBalita::where('id', $id)->first();

        $pdf = PDF::loadView('home.parent.pdf', compact('data', 'data_balita'));

        return $pdf->stream('hasil-pemeriksaan.pdf');  
    }

    public function getPDFPeriksa()
    {
        return $this->doPeriksaBalita();
    }
    
}
