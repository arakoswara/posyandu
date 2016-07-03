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
        $jml_balita = DataBalita::all()->count(); 

        return view('visitor.index', compact('jml_balita'));

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
        
        $grafik_score = Score::orderBy('id', 'ASC')->where('id_balita', $id)->get();

        return view('visitor.balita.detail', compact('data_balita', 'score', 'grafik_score'));
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
         * temporary variabel dari form periksa
         */
        $input = $request->all();

        $date = date_create($input['tgl_periksa']);

        $input['tgl_periksa'] = date_format($date, 'd-m-Y');

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
        $periksa_balita = Periksa::with('dataBalita')->where('id_balita',$id_balita)->orderBy('id', 'DESC')->first();

        $time=strtotime($periksa_balita->tgl_periksa);

        $month=date("F",$time);

        /**
         * Hitung umur balita
         */
        $umur = (strtotime($periksa_balita->tgl_periksa) - strtotime($periksa_balita->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30);

        /**
         * pembulatan umur
         */
        $umur_bulat = floor($umur);

        $this->perhitunganScore($periksa_balita, $umur_bulat, $month);

        // return "Oke";
    }

    public function perhitunganScore($periksa_balita, $umur_bulat, $month)
    {
        /**
         * HITUNG BBU
         */
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
        
        $input['month']     = $month;

        $input['id_balita'] = $periksa_balita->id_balita;

        $input['id_periksa']= $periksa_balita->id;

        $input['zbbu']      = $zbbu;

        $input['ztbu']      = $ztbu;

        $input['zbbtb']     = $zbbtb;

        Score::create($input);
    }

    public function cobaEnergi()
    {
        return $this->zbbuGizikurang();
    }

    /**
     * ZBBU
     * =================================================
     */
    public function zbbuGiziburuk()
    {
        $score = Score::with('periksa')->where('id_periksa', 1)->with('dataBalita')->where('id_balita', 1)->first();

        if ($score->zbbu <= -3) {
            
            $gizi_buruk = 1;

        }else if($score->zbbu >= -3 && $score->zbbu <= -2) {

            $gizi_buruk = (-2 - $score->zbbu);

        }else if($score->zbbu >= -2) {

            $gizi_buruk = 0;

        }

        return $gizi_buruk;

    }


    public function zbbuGizikurang()
    {
        $score = Score::with('periksa')->where('id_periksa', 1)->with('dataBalita')->where('id_balita', 1)->first();

        if ($score->zbbu <= -3 || $score->zbbu >= 0) {
            
             $gizi_kurang = 0;

        }else if($score->zbbu >= -3 && $score->zbbu <= -2) {

             $gizi_kurang = $score->zbbu + 3;

        }else if($score->zbbu >= -2 && $score->zbbu <= 0) {

             $gizi_kurang = (-$score->zbbu)/2;

        }else if($score->zbbu == -2) {

             $gizi_kurang = 1;
        }
		return $gizi_kurang;

    }

    public function zbbuGizibaik()
    {
        $score = Score::with('periksa')->where('id_periksa', 1)->with('dataBalita')->where('id_balita', 1)->first();

        if ($score->zbbu <= -2 || $score->zbbu >= 2) {
            
            $gizi_baik = 0;

        }else if($score->zbbu >= -2 && $score->zbbu <= 0) {

            $gizi_baik = ($score->zbbu + 2)/2;

        }else if($score->zbbu >= 0 && $score->zbbu <= 2) {

            $gizi_baik = (2 - $score->zbbu)/2;

        }else if($score->zbbu == 0) {

            $gizi_baik = 1;
        }
        return $gizi_baik;

    }

    public function zbbuGizilebih()
    {
        $score = Score::with('periksa')->where('id_periksa', 1)->with('dataBalita')->where('id_balita', 1)->first();

        if ($score->zbbu >= 0 && $score->zbbu <= 2) {
            
            $gizi_lebih = ($score->zbbu)/2;

        }else if($score->zbbu >= 2) {

            $gizi_lebih = 1;

        }else if($score->zbbu <= 0) {

            $gizi_lebih = 0;
        }

        return $gizi_lebih;

    }

    /**
     * ZTBU
     * ============================================
     */

    public function ztbuSangatPendek()
    {
        $score = Score::with('periksa')->where('id_periksa', 1)->with('dataBalita')->where('id_balita', 1)->first();

        if ($score->ztbu <= -3) {
            
            return $sangat_pendek = 1;

        }else if($score->ztbu >= -3 && $score->ztbu <= -2) {

            return $sangat_pendek = (-2 - $score->ztbu);

        }else if($score->ztbu >= -2) {

            return $sangat_pendek = 0;

        }

    }


    public function ztbuPendek()
    {
        $score = Score::with('periksa')->where('id_periksa', 1)->with('dataBalita')->where('id_balita', 1)->first();

        if ($score->ztbu <= -3 || $score->ztbu >= 0) {
            
            return $pendek = 0;

        }else if($score->ztbu >= -3 && $score->ztbu <= -2) {

            return $pendek = $score->ztbu + 3;

        }else if($score->ztbu >= -2 && $score->ztbu <= 0) {

            return $pendek = (-$score->ztbu)/2;

        }else if($score->ztbu == -2) {

            return $pendek = 1;
        }

    }

    public function ztbuNormal()
    {
        $score = Score::with('periksa')->where('id_periksa', 1)->with('dataBalita')->where('id_balita', 1)->first();

        if ($score->ztbu <= -2 || $score->ztbu >= 2) {
            
            return $normal_ztbu = 0;

        }else if($score->ztbu >= -2 && $score->ztbu <= 0) {

            return $normal_ztbu = ($score->ztbu + 2)/2;

        }else if($score->ztbu >= 0 && $score->ztbu <= 2) {

            return $normal_ztbu = (2 - $score->ztbu)/2;

        }else if($score->ztbu == 0) {

            return $normal_ztbu = 1;
        }

    }

    public function ztbuTinggi()
    {
        $score = Score::with('periksa')->where('id_periksa', 1)->with('dataBalita')->where('id_balita', 1)->first();

        if ($score->ztbu >= 0 && $score->ztbu <= 2) {
            
            $tinggi = ($score->ztbu)/2;

        }else if($score->ztbu >= 2) {

            $tinggi = 1;

        }else if($score->ztbu <= 0) {

            $tinggi = 0;
        }

        return $tinggi;

    }


    /**
     * ZBBTB
     * ============================================
     */
    public function zbbtbSangatKurus()
    {
        $score = Score::with('periksa')->where('id_periksa', 1)->with('dataBalita')->where('id_balita', 1)->first();

        if ($score->zbbtb <= -3) {
            
            return $sangat_kurus = 1;

        }else if($score->zbbtb >= -3 && $score->zbbtb <= -2) {

            return $sangat_kurus = (-2 - $score->zbbtb);

        }else if($score->zbbtb >= -2) {

            return $sangat_kurus = 0;

        }

    }

    public function zbbtbKurus()
    {
        $score = Score::with('periksa')->where('id_periksa', 1)->with('dataBalita')->where('id_balita', 1)->first();

        if ($score->zbbtb <= -3 || $score->zbbtb >= 0) {
            
            return $kurus = 0;

        }else if($score->zbbtb >= -3 && $score->zbbtb <= -2) {

            return $kurus = $score->zbbtb + 3;

        }else if($score->zbbtb >= -2 && $score->zbbtb <= 0) {

            return $kurus = (-$score->zbbtb)/2;

        }else if($score->zbbtb == -2) {

            return $kurus = 1;
        }

    }

    public function zbbtbNormal()
    {
        $score = Score::with('periksa')->where('id_periksa', 1)->with('dataBalita')->where('id_balita', 1)->first();

        if ($score->zbbtb <= -2 || $score->zbbtb >= 2) {
            
            $normal_zbbtb = 0;

        }else if($score->zbbtb >= -2 && $score->zbbtb <= 0) {

            $normal_zbbtb = ($score->zbbtb + 2)/2;

        }else if($score->zbbtb >= 0 && $score->zbbtb <= 2) {

            $normal_zbbtb = (2 - $score->zbbtb)/2;

        }else if($score->zbbtb == 0) {

            $normal_zbbtb = 1;
        }

        return $normal_zbbtb;

    }

    public function zbbtbGemuk()
    {
        $score = Score::with('periksa')->where('id_periksa', 1)->with('dataBalita')->where('id_balita', 1)->first();

        if ($score->zbbtb >= 0 && $score->zbbtb <= 2) {
            
            return $gemuk = ($score->zbbtb)/2;

        }else if($score->zbbtb >= 2) {

            return $gemuk = 1;

        }else if($score->zbbtb <= 0) {

            return $gemuk = 0;
        }

        return $gemuk;

    }

    /**
     * Rule 1
	 * R1 Zbbu(gizilebih) &Ztbu(Tinggi) & Zbbtb(Normal) z1= energi-(0.2*energi)  y1=diit
     * ============================================
     */	
    public function R_1($gizi_lebih = 0, $tinggi = 1, $normal_zbbtb = 0.95454545)
    {
        $score = Score::with('periksa')->where('id_periksa', 1)->with('dataBalita')->where('id_balita', 1)->first();


        // return $score->periksa->berat_badan;
        
        $umur_tahun = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30 *12);
        /**
         * Pembulatan umur ke tahun
         */
        $umur_tahun_bulat = floor($umur_tahun);

        /**
         * CARI ENERGI
         */
        if ($umur_tahun_bulat <= 1) {
            
            $energi = 110 * $score->periksa->berat_badan;  

        } else if ($umur_tahun_bulat <= 3) {

            $energi = 100 * $score->periksa->berat_badan;

        } else if ($umur_tahun_bulat <= 5) {

            $energi = 90 * $score->periksa->berat_badan;
        }

        // return $energi;

        /**
         * CARI PROTEIN
         */
        
        $umur_bulan = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30);

        $umur_bulan_bulat = floor($umur_bulan);

        /**
         * Protein DIIT
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_diit = 1.84 * $score->periksa->berat_badan;

        }else{

            $protein_diit =  1.79 * $score->periksa->berat_badan;
        }

        // return $protein_diit;

        /**
         * Protein KKP
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_kkp = 2.05 * $score->periksa->berat_badan;

        }else{

            $protein_kkp =  2.03 * $score->periksa->berat_badan;
        }

        return $protein_kkp;
    }

    public function FunctionName($value='')
    {
        /**
         * Sampe sini
         */

        // return $protein_kkp;
        
        /**
         * Menghitung R 1
         */
        $z1 = $energi - (0.2 * $energi);

        $y1 = $protein_diit;

        // $rz1=r_1*$z1;

        // $ry1=r_1*$y1;
        

    }

    /**
     * Rule 2
	 * R2 Zbbu(gizilebih) &Ztbu(Normal)  & Zbbtb(Gemuk) z2= energi-(0.1*energi) y2=diit
     * ============================================
     */
	
    public function R_2($gizi_lebih = 0, $normal_ztbu = 0.180555, $gemuk = 0.04545455)
    {

        $score = Score::with('periksa')->where('id_periksa', 1)->with('dataBalita')->where('id_balita', 1)->first();


        // return $score->periksa->berat_badan;
        
        $umur_tahun = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30 *12);
        /**
         * Pembulatan umur ke tahun
         */
        $umur_tahun_bulat = floor($umur_tahun);

        /**
         * CARI ENERGI
         */
        if ($umur_tahun_bulat <= 1) {
            
            $energi = 110 * $score->periksa->berat_badan;  

        } else if ($umur_tahun_bulat <= 3) {

            $energi = 100 * $score->periksa->berat_badan;

        } else if ($umur_tahun_bulat <= 5) {

            $energi = 90 * $score->periksa->berat_badan;
        }

        // return $energi;

        /**
         * CARI PROTEIN
         */
        
        $umur_bulan = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30);

        $umur_bulan_bulat = floor($umur_bulan);

        /**
         * Protein DIIT
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_diit = 1.84 * $score->periksa->berat_badan;

        }else{

            $protein_diit =  1.79 * $score->periksa->berat_badan;
        }

        return $protein_diit;

        /**
         * Protein KKP
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_kkp = 2.05 * $score->periksa->berat_badan;

        }else{

            $protein_kkp =  2.03 * $score->periksa->berat_badan;
        }

        //return $protein_kkp;
        
        /**
         * Menghitung R 2
         */
        $z2 = $energi - (0.1 * $energi);

        $y2 = $protein_diit;
        
    }
    /**
     * Rule 3
	 * R3 Zbbu(gizilebih) &Ztbu(Pendek) & Zbbtb(Gemuk) z3=energi-(0.1*energi) y3=diit
     * ============================================
     */

    public function R_3($gizi_lebih = 0, $pendek = 0.819445, $gemuk = 0.04545455)
    {

        $score = Score::with('periksa')->where('id_periksa', 1)->with('dataBalita')->where('id_balita', 1)->first();


        // return $score->periksa->berat_badan;
        
        $umur_tahun = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30 *12);
        /**
         * Pembulatan umur ke tahun
         */
        $umur_tahun_bulat = floor($umur_tahun);

        /**
         * CARI ENERGI
         */
        if ($umur_tahun_bulat <= 1) {
            
            $energi = 110 * $score->periksa->berat_badan;  

        } else if ($umur_tahun_bulat <= 3) {

            $energi = 100 * $score->periksa->berat_badan;

        } else if ($umur_tahun_bulat <= 5) {

            $energi = 90 * $score->periksa->berat_badan;
        }

        // return $energi;

        /**
         * CARI PROTEIN
         */
        
        $umur_bulan = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30);

        $umur_bulan_bulat = floor($umur_bulan);

        /**
         * Protein DIIT
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_diit = 1.84 * $score->periksa->berat_badan;

        }else{

            $protein_diit =  1.79 * $score->periksa->berat_badan;
        }

        return $protein_diit;

        /**
         * Protein KKP
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_kkp = 2.05 * $score->periksa->berat_badan;

        }else{

            $protein_kkp =  2.03 * $score->periksa->berat_badan;
        }

        //return $protein_kkp;
        
        /**
         * Menghitung R 3
         */
        $z3 = $energi - (0.1 * $energi);

        $y3 = $protein_diit;
        
    }
    /**
     * Rule 4
     * R4 Zbbu(gizilebih) &Ztbu(SangatPendek) & Zbbtb(Gemuk) z4=energi-(0.2*energi) y4=diit
     * ============================================
     */

    public function R_4($gizi_lebih = 0, $sangat_pendek = 0, $gemuk = 0.04545455)
    {

        $score = Score::with('periksa')->where('id_periksa', 1)->with('dataBalita')->where('id_balita', 1)->first();


        // return $score->periksa->berat_badan;
        
        $umur_tahun = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30 *12);
        /**
         * Pembulatan umur ke tahun
         */
        $umur_tahun_bulat = floor($umur_tahun);

        /**
         * CARI ENERGI
         */
        if ($umur_tahun_bulat <= 1) {
            
            $energi = 110 * $score->periksa->berat_badan;  

        } else if ($umur_tahun_bulat <= 3) {

            $energi = 100 * $score->periksa->berat_badan;

        } else if ($umur_tahun_bulat <= 5) {

            $energi = 90 * $score->periksa->berat_badan;
        }

        // return $energi;

        /**
         * CARI PROTEIN
         */
        
        $umur_bulan = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30);

        $umur_bulan_bulat = floor($umur_bulan);

        /**
         * Protein DIIT
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_diit = 1.84 * $score->periksa->berat_badan;

        }else{

            $protein_diit =  1.79 * $score->periksa->berat_badan;
        }

        return $protein_diit;

        /**
         * Protein KKP
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_kkp = 2.05 * $score->periksa->berat_badan;

        }else{

            $protein_kkp =  2.03 * $score->periksa->berat_badan;
        }

        //return $protein_kkp;
        
        /**
         * Menghitung R 4
         */
        $z4 = $energi - (0.2 * $energi);

        $y4 = $protein_diit;
        
    }
    /**
     * Rule 5
     * R5 Zbbu(gizibaik) &Ztbu(Tinggi) & Zbbtb(Normal) z5= energi y5=diit
     * ============================================
     */

    public function R_5($gizi_baik = 0.59375, $tinggi = 0, $normal_zbbtb = 0.95454545)
    {

        $score = Score::with('periksa')->where('id_periksa', 1)->with('dataBalita')->where('id_balita', 1)->first();


        // return $score->periksa->berat_badan;
        
        $umur_tahun = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30 *12);
        /**
         * Pembulatan umur ke tahun
         */
        $umur_tahun_bulat = floor($umur_tahun);

        /**
         * CARI ENERGI
         */
        if ($umur_tahun_bulat <= 1) {
            
            $energi = 110 * $score->periksa->berat_badan;  

        } else if ($umur_tahun_bulat <= 3) {

            $energi = 100 * $score->periksa->berat_badan;

        } else if ($umur_tahun_bulat <= 5) {

            $energi = 90 * $score->periksa->berat_badan;
        }

        // return $energi;

        /**
         * CARI PROTEIN
         */
        
        $umur_bulan = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30);

        $umur_bulan_bulat = floor($umur_bulan);

        /**
         * Protein DIIT
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_diit = 1.84 * $score->periksa->berat_badan;

        }else{

            $protein_diit =  1.79 * $score->periksa->berat_badan;
        }

        return $protein_diit;

        /**
         * Protein KKP
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_kkp = 2.05 * $score->periksa->berat_badan;

        }else{

            $protein_kkp =  2.03 * $score->periksa->berat_badan;
        }

        //return $protein_kkp;
        
        /**
         * Menghitung R 5
         */
        $z5 = $energi;

        $y5 = $protein_diit;
        
    }  

    /**
     * Rule 6
     * R6 min( Zbbu(gizibaik) &Ztbu(Normal) & Zbbtb(Normal) z6= energi y6=diit
     * ============================================
     */

    public function R_6($gizi_baik = 0.59375, $normal_ztbu = 0.180555, $normal_zbbtb = 0.95454545)
    {

        $score = Score::with('periksa')->where('id_periksa', 1)->with('dataBalita')->where('id_balita', 1)->first();


        // return $score->periksa->berat_badan;
        
        $umur_tahun = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30 *12);
        /**
         * Pembulatan umur ke tahun
         */
        $umur_tahun_bulat = floor($umur_tahun);

        /**
         * CARI ENERGI
         */
        if ($umur_tahun_bulat <= 1) {
            
            $energi = 110 * $score->periksa->berat_badan;  

        } else if ($umur_tahun_bulat <= 3) {

            $energi = 100 * $score->periksa->berat_badan;

        } else if ($umur_tahun_bulat <= 5) {

            $energi = 90 * $score->periksa->berat_badan;
        }

        // return $energi;

        /**
         * CARI PROTEIN
         */
        
        $umur_bulan = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30);

        $umur_bulan_bulat = floor($umur_bulan);

        /**
         * Protein DIIT
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_diit = 1.84 * $score->periksa->berat_badan;

        }else{

            $protein_diit =  1.79 * $score->periksa->berat_badan;
        }

        return $protein_diit;

        /**
         * Protein KKP
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_kkp = 2.05 * $score->periksa->berat_badan;

        }else{

            $protein_kkp =  2.03 * $score->periksa->berat_badan;
        }

        //return $protein_kkp;
        
        /**
         * Menghitung R 6
         */
        $z6 = $energi;

        $y6 = $protein_diit;
        
    }

    /**
     * Rule 7
     * R7 min( Zbbu(gizibaik) &Ztbu(Pendek) & Zbbtb(Normal) z7= energi y7=diit
     * ============================================
     */

    public function R_7($gizi_baik = 0.59375, $pendek = 0.819445, $normal_zbbtb = 0.95454545)
    {

        $score = Score::with('periksa')->where('id_periksa', 1)->with('dataBalita')->where('id_balita', 1)->first();


        // return $score->periksa->berat_badan;
        
        $umur_tahun = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30 *12);
        /**
         * Pembulatan umur ke tahun
         */
        $umur_tahun_bulat = floor($umur_tahun);

        /**
         * CARI ENERGI
         */
        if ($umur_tahun_bulat <= 1) {
            
            $energi = 110 * $score->periksa->berat_badan;  

        } else if ($umur_tahun_bulat <= 3) {

            $energi = 100 * $score->periksa->berat_badan;

        } else if ($umur_tahun_bulat <= 5) {

            $energi = 90 * $score->periksa->berat_badan;
        }

        // return $energi;

        /**
         * CARI PROTEIN
         */
        
        $umur_bulan = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30);

        $umur_bulan_bulat = floor($umur_bulan);

        /**
         * Protein DIIT
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_diit = 1.84 * $score->periksa->berat_badan;

        }else{

            $protein_diit =  1.79 * $score->periksa->berat_badan;
        }

        return $protein_diit;

        /**
         * Protein KKP
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_kkp = 2.05 * $score->periksa->berat_badan;

        }else{

            $protein_kkp =  2.03 * $score->periksa->berat_badan;
        }

        //return $protein_kkp;
        
        /**
         * Menghitung R 7
         */
        $z7 = $energi;

        $y7 = $protein_diit;
        
    }

    /**
     * Rule 8
     * R8 min( Zbbu(gizibaik) &Ztbu(SangatPendek) & Zbbtb(Gemuk) z8= energi y8=diit
     * ============================================
     */

    public function R_8($gizi_baik = 0.59375, $sangat_pendek = 0, $gemuk = 0.04545455)
    {

        $score = Score::with('periksa')->where('id_periksa', 1)->with('dataBalita')->where('id_balita', 1)->first();


        // return $score->periksa->berat_badan;
        
        $umur_tahun = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30 *12);
        /**
         * Pembulatan umur ke tahun
         */
        $umur_tahun_bulat = floor($umur_tahun);

        /**
         * CARI ENERGI
         */
        if ($umur_tahun_bulat <= 1) {
            
            $energi = 110 * $score->periksa->berat_badan;  

        } else if ($umur_tahun_bulat <= 3) {

            $energi = 100 * $score->periksa->berat_badan;

        } else if ($umur_tahun_bulat <= 5) {

            $energi = 90 * $score->periksa->berat_badan;
        }

        // return $energi;

        /**
         * CARI PROTEIN
         */
        
        $umur_bulan = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30);

        $umur_bulan_bulat = floor($umur_bulan);

        /**
         * Protein DIIT
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_diit = 1.84 * $score->periksa->berat_badan;

        }else{

            $protein_diit =  1.79 * $score->periksa->berat_badan;
        }

        return $protein_diit;

        /**
         * Protein KKP
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_kkp = 2.05 * $score->periksa->berat_badan;

        }else{

            $protein_kkp =  2.03 * $score->periksa->berat_badan;
        }

        //return $protein_kkp;
        
        /**
         * Menghitung R 8
         */
        $z8 = $energi;

        $y8 = $protein_diit;
        
    }

    /**
     * Rule 9
     * R9 min( Zbbu(gizibaik) &Ztbu(SangatPendek) & Zbbtb(Normal) z9= energi y9=diit
     * ============================================
     */

    public function R_9($gizi_baik = 0.59375, $sangat_pendek = 0, $normal_zbbtb = 0.95454545)
    {

        $score = Score::with('periksa')->where('id_periksa', 1)->with('dataBalita')->where('id_balita', 1)->first();


        // return $score->periksa->berat_badan;
        
        $umur_tahun = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30 *12);
        /**
         * Pembulatan umur ke tahun
         */
        $umur_tahun_bulat = floor($umur_tahun);

        /**
         * CARI ENERGI
         */
        if ($umur_tahun_bulat <= 1) {
            
            $energi = 110 * $score->periksa->berat_badan;  

        } else if ($umur_tahun_bulat <= 3) {

            $energi = 100 * $score->periksa->berat_badan;

        } else if ($umur_tahun_bulat <= 5) {

            $energi = 90 * $score->periksa->berat_badan;
        }

        // return $energi;

        /**
         * CARI PROTEIN
         */
        
        $umur_bulan = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30);

        $umur_bulan_bulat = floor($umur_bulan);

        /**
         * Protein DIIT
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_diit = 1.84 * $score->periksa->berat_badan;

        }else{

            $protein_diit =  1.79 * $score->periksa->berat_badan;
        }

        return $protein_diit;

        /**
         * Protein KKP
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_kkp = 2.05 * $score->periksa->berat_badan;

        }else{

            $protein_kkp =  2.03 * $score->periksa->berat_badan;
        }

        //return $protein_kkp;
        
        /**
         * Menghitung R 9
         */
        $z9 = $energi;

        $y9 = $protein_diit;
        
    }

    /**
     * Rule 10
     * R10 min( Zbbu(gizikurang) &Ztbu(Tinggi) & Zbbtb(Sangat Kurus) z10 = energi+(0.4*energi) y10=diit
     * ============================================
     */

    public function R_10($gizi_kurang = 0.40625, $tinggi = 0, $sangat_kurus = 0)
    {

        $score = Score::with('periksa')->where('id_periksa', 1)->with('dataBalita')->where('id_balita', 1)->first();


        // return $score->periksa->berat_badan;
        
        $umur_tahun = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30 *12);
        /**
         * Pembulatan umur ke tahun
         */
        $umur_tahun_bulat = floor($umur_tahun);

        /**
         * CARI ENERGI
         */
        if ($umur_tahun_bulat <= 1) {
            
            $energi = 110 * $score->periksa->berat_badan;  

        } else if ($umur_tahun_bulat <= 3) {

            $energi = 100 * $score->periksa->berat_badan;

        } else if ($umur_tahun_bulat <= 5) {

            $energi = 90 * $score->periksa->berat_badan;
        }

        // return $energi;

        /**
         * CARI PROTEIN
         */
        
        $umur_bulan = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30);

        $umur_bulan_bulat = floor($umur_bulan);

        /**
         * Protein DIIT
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_diit = 1.84 * $score->periksa->berat_badan;

        }else{

            $protein_diit =  1.79 * $score->periksa->berat_badan;
        }

        return $protein_diit;

        /**
         * Protein KKP
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_kkp = 2.05 * $score->periksa->berat_badan;

        }else{

            $protein_kkp =  2.03 * $score->periksa->berat_badan;
        }

        //return $protein_kkp;
        
        /**
         * Menghitung R 10
         */
        $z10 = $energi + (0.4 * $energi);

        $y10 = $protein_diit;
        
    }

    /**
     * Rule 11
     * R11 min( Zbbu(gizikurang) &Ztbu(Normal) & Zbbtb(Kurus) z11= energi+(0.2*energi) y11=diit
     * ============================================
     */

    public function R_11($gizi_kurang = 0.40625, $normal_ztbu = 0.180555, $kurus = 0)
    {

        $score = Score::with('periksa')->where('id_periksa', 1)->with('dataBalita')->where('id_balita', 1)->first();


        // return $score->periksa->berat_badan;
        
        $umur_tahun = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30 *12);
        /**
         * Pembulatan umur ke tahun
         */
        $umur_tahun_bulat = floor($umur_tahun);

        /**
         * CARI ENERGI
         */
        if ($umur_tahun_bulat <= 1) {
            
            $energi = 110 * $score->periksa->berat_badan;  

        } else if ($umur_tahun_bulat <= 3) {

            $energi = 100 * $score->periksa->berat_badan;

        } else if ($umur_tahun_bulat <= 5) {

            $energi = 90 * $score->periksa->berat_badan;
        }

        // return $energi;

        /**
         * CARI PROTEIN
         */
        
        $umur_bulan = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30);

        $umur_bulan_bulat = floor($umur_bulan);

        /**
         * Protein DIIT
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_diit = 1.84 * $score->periksa->berat_badan;

        }else{

            $protein_diit =  1.79 * $score->periksa->berat_badan;
        }

        return $protein_diit;

        /**
         * Protein KKP
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_kkp = 2.05 * $score->periksa->berat_badan;

        }else{

            $protein_kkp =  2.03 * $score->periksa->berat_badan;
        }

        //return $protein_kkp;
        
        /**
         * Menghitung R 11
         */
        $z11 = $energi + (0.2 * $energi);

        $y11 = $protein_diit;
        
    }

    /**
     * Rule 12
     * R12 min( Zbbu(gizikurang) &Ztbu(Pendek) & Zbbtb(Normal) z12 = energi+(0.2*energi) y12=diit
     * ============================================
     */

    public function R_12($gizi_kurang = 0.40625, $pendek = 0.819445, $normal_zbbtb = 0.95454545)
    {

        $score = Score::with('periksa')->where('id_periksa', 1)->with('dataBalita')->where('id_balita', 1)->first();


        // return $score->periksa->berat_badan;
        
        $umur_tahun = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30 *12);
        /**
         * Pembulatan umur ke tahun
         */
        $umur_tahun_bulat = floor($umur_tahun);

        /**
         * CARI ENERGI
         */
        if ($umur_tahun_bulat <= 1) {
            
            $energi = 110 * $score->periksa->berat_badan;  

        } else if ($umur_tahun_bulat <= 3) {

            $energi = 100 * $score->periksa->berat_badan;

        } else if ($umur_tahun_bulat <= 5) {

            $energi = 90 * $score->periksa->berat_badan;
        }

        // return $energi;

        /**
         * CARI PROTEIN
         */
        
        $umur_bulan = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30);

        $umur_bulan_bulat = floor($umur_bulan);

        /**
         * Protein DIIT
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_diit = 1.84 * $score->periksa->berat_badan;

        }else{

            $protein_diit =  1.79 * $score->periksa->berat_badan;
        }

        return $protein_diit;

        /**
         * Protein KKP
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_kkp = 2.05 * $score->periksa->berat_badan;

        }else{

            $protein_kkp =  2.03 * $score->periksa->berat_badan;
        }

        //return $protein_kkp;
        
        /**
         * Menghitung R 12
         */
        $z12 = $energi + (0.2 * $energi);

        $y12 = $protein_diit;
        
    }

    /**
     * Rule 13
     * R13 min( Zbbu(gizikurang) &Ztbu(Pendek) & Zbbtb(Kurus) z13= energi+(0.3*energi) y13=diit
     * ============================================
     */

    public function R_13($gizi_kurang = 0.40625, $pendek = 0.819445, $kurus = 0)
    {

        $score = Score::with('periksa')->where('id_periksa', 1)->with('dataBalita')->where('id_balita', 1)->first();


        // return $score->periksa->berat_badan;
        
        $umur_tahun = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30 *12);
        /**
         * Pembulatan umur ke tahun
         */
        $umur_tahun_bulat = floor($umur_tahun);

        /**
         * CARI ENERGI
         */
        if ($umur_tahun_bulat <= 1) {
            
            $energi = 110 * $score->periksa->berat_badan;  

        } else if ($umur_tahun_bulat <= 3) {

            $energi = 100 * $score->periksa->berat_badan;

        } else if ($umur_tahun_bulat <= 5) {

            $energi = 90 * $score->periksa->berat_badan;
        }

        // return $energi;

        /**
         * CARI PROTEIN
         */
        
        $umur_bulan = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30);

        $umur_bulan_bulat = floor($umur_bulan);

        /**
         * Protein DIIT
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_diit = 1.84 * $score->periksa->berat_badan;

        }else{

            $protein_diit =  1.79 * $score->periksa->berat_badan;
        }

        return $protein_diit;

        /**
         * Protein KKP
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_kkp = 2.05 * $score->periksa->berat_badan;

        }else{

            $protein_kkp =  2.03 * $score->periksa->berat_badan;
        }

        //return $protein_kkp;
        
        /**
         * Menghitung R 13
         */
        $z13 = $energi + (0.3 * $energi);

        $y13 = $protein_diit;
        
    }

    /**
     * Rule 14
     * R14 min( Zbbu(gizikurang) &Ztbu(SangatPendek) & Zbbtb(Normal) z14 =energi+(0.3*energi) y14=diit
     * ============================================
     */

    public function R_14($gizi_kurang = 0.40625, $sangat_pendek = 0, $normal_zbbtb = 0.95454545)
    {

        $score = Score::with('periksa')->where('id_periksa', 1)->with('dataBalita')->where('id_balita', 1)->first();


        // return $score->periksa->berat_badan;
        
        $umur_tahun = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30 *12);
        /**
         * Pembulatan umur ke tahun
         */
        $umur_tahun_bulat = floor($umur_tahun);

        /**
         * CARI ENERGI
         */
        if ($umur_tahun_bulat <= 1) {
            
            $energi = 110 * $score->periksa->berat_badan;  

        } else if ($umur_tahun_bulat <= 3) {

            $energi = 100 * $score->periksa->berat_badan;

        } else if ($umur_tahun_bulat <= 5) {

            $energi = 90 * $score->periksa->berat_badan;
        }

        // return $energi;

        /**
         * CARI PROTEIN
         */
        
        $umur_bulan = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30);

        $umur_bulan_bulat = floor($umur_bulan);

        /**
         * Protein DIIT
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_diit = 1.84 * $score->periksa->berat_badan;

        }else{

            $protein_diit =  1.79 * $score->periksa->berat_badan;
        }

        return $protein_diit;

        /**
         * Protein KKP
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_kkp = 2.05 * $score->periksa->berat_badan;

        }else{

            $protein_kkp =  2.03 * $score->periksa->berat_badan;
        }

        //return $protein_kkp;
        
        /**
         * Menghitung R 14
         */
        $z14 = $energi + (0.3 * $energi);

        $y14 = $protein_diit;
        
    }

    /**
     * Rule 15
     * R15 min( Zbbu(giziburuk) &Ztbu(Tinggi) & Zbbtb(SangatKurus) z15= energi+(0.4*energi) y15=kkp
     * ============================================
     */

    public function R_15($gizi_buruk = 0, $tinggi = 0, $sangat_kurus = 0)
    {

        $score = Score::with('periksa')->where('id_periksa', 1)->with('dataBalita')->where('id_balita', 1)->first();


        // return $score->periksa->berat_badan;
        
        $umur_tahun = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30 *12);
        /**
         * Pembulatan umur ke tahun
         */
        $umur_tahun_bulat = floor($umur_tahun);

        /**
         * CARI ENERGI
         */
        if ($umur_tahun_bulat <= 1) {
            
            $energi = 110 * $score->periksa->berat_badan;  

        } else if ($umur_tahun_bulat <= 3) {

            $energi = 100 * $score->periksa->berat_badan;

        } else if ($umur_tahun_bulat <= 5) {

            $energi = 90 * $score->periksa->berat_badan;
        }

        // return $energi;

        /**
         * CARI PROTEIN
         */
        
        $umur_bulan = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30);

        $umur_bulan_bulat = floor($umur_bulan);

        /**
         * Protein DIIT
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_diit = 1.84 * $score->periksa->berat_badan;

        }else{

            $protein_diit =  1.79 * $score->periksa->berat_badan;
        }

        return $protein_diit;

        /**
         * Protein KKP
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_kkp = 2.05 * $score->periksa->berat_badan;

        }else{

            $protein_kkp =  2.03 * $score->periksa->berat_badan;
        }

        //return $protein_kkp;
        
        /**
         * Menghitung R 15
         */
        $z15 = $energi + (0.4 * $energi);

        $y15 = $protein_kkp;
        
    }

    /**
     * Rule 16
     * R16 min( Zbbu(giziburuk) &Ztbu(Normal) & Zbbtb(Kurus) z16 = energi+(0.3*energi) y16=kkp
     * ============================================
     */

    public function R_16($gizi_buruk = 0, $normal_ztbu = 0.180555, $kurus = 0)
    {

        $score = Score::with('periksa')->where('id_periksa', 1)->with('dataBalita')->where('id_balita', 1)->first();


        // return $score->periksa->berat_badan;
        
        $umur_tahun = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30 *12);
        /**
         * Pembulatan umur ke tahun
         */
        $umur_tahun_bulat = floor($umur_tahun);

        /**
         * CARI ENERGI
         */
        if ($umur_tahun_bulat <= 1) {
            
            $energi = 110 * $score->periksa->berat_badan;  

        } else if ($umur_tahun_bulat <= 3) {

            $energi = 100 * $score->periksa->berat_badan;

        } else if ($umur_tahun_bulat <= 5) {

            $energi = 90 * $score->periksa->berat_badan;
        }

        // return $energi;

        /**
         * CARI PROTEIN
         */
        
        $umur_bulan = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30);

        $umur_bulan_bulat = floor($umur_bulan);

        /**
         * Protein DIIT
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_diit = 1.84 * $score->periksa->berat_badan;

        }else{

            $protein_diit =  1.79 * $score->periksa->berat_badan;
        }

        return $protein_diit;

        /**
         * Protein KKP
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_kkp = 2.05 * $score->periksa->berat_badan;

        }else{

            $protein_kkp =  2.03 * $score->periksa->berat_badan;
        }

        //return $protein_kkp;
        
        /**
         * Menghitung R 16
         */
        $z16 = $energi + (0.3 * $energi);

        $y16 = $protein_kkp;
        
    }

    /**
     * Rule 17
     * R17 min( Zbbu(giziburuk) &Ztbu(Pendek) & Zbbtb(Kurus) z17= energi+(0.4*energi) y17=kkp
     * ============================================
     */

    public function R_17($gizi_buruk = 0, $pendek = 0.819445, $kurus = 0)
    {

        $score = Score::with('periksa')->where('id_periksa', 1)->with('dataBalita')->where('id_balita', 1)->first();


        // return $score->periksa->berat_badan;
        
        $umur_tahun = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30 *12);
        /**
         * Pembulatan umur ke tahun
         */
        $umur_tahun_bulat = floor($umur_tahun);

        /**
         * CARI ENERGI
         */
        if ($umur_tahun_bulat <= 1) {
            
            $energi = 110 * $score->periksa->berat_badan;  

        } else if ($umur_tahun_bulat <= 3) {

            $energi = 100 * $score->periksa->berat_badan;

        } else if ($umur_tahun_bulat <= 5) {

            $energi = 90 * $score->periksa->berat_badan;
        }

        // return $energi;

        /**
         * CARI PROTEIN
         */
        
        $umur_bulan = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30);

        $umur_bulan_bulat = floor($umur_bulan);

        /**
         * Protein DIIT
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_diit = 1.84 * $score->periksa->berat_badan;

        }else{

            $protein_diit =  1.79 * $score->periksa->berat_badan;
        }

        return $protein_diit;

        /**
         * Protein KKP
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_kkp = 2.05 * $score->periksa->berat_badan;

        }else{

            $protein_kkp =  2.03 * $score->periksa->berat_badan;
        }

        //return $protein_kkp;
        
        /**
         * Menghitung R 17
         */
        $z17 = $energi + (0.4 * $energi);

        $y17 = $protein_kkp;
        
    }

    /**
     * Rule 18
     * R18 min( Zbbu(giziburuk) &Ztbu(SangatPendek) & Zbbtb(Normal) z18= energi+(0.3*energi) y18=kkp
     * ============================================
     */

    public function R_18($gizi_buruk = 0, $sangat_pendek = 0, $normal_zbbtb = 0.95454545)
    {

        $score = Score::with('periksa')->where('id_periksa', 1)->with('dataBalita')->where('id_balita', 1)->first();


        // return $score->periksa->berat_badan;
        
        $umur_tahun = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30 *12);
        /**
         * Pembulatan umur ke tahun
         */
        $umur_tahun_bulat = floor($umur_tahun);

        /**
         * CARI ENERGI
         */
        if ($umur_tahun_bulat <= 1) {
            
            $energi = 110 * $score->periksa->berat_badan;  

        } else if ($umur_tahun_bulat <= 3) {

            $energi = 100 * $score->periksa->berat_badan;

        } else if ($umur_tahun_bulat <= 5) {

            $energi = 90 * $score->periksa->berat_badan;
        }

        // return $energi;

        /**
         * CARI PROTEIN
         */
        
        $umur_bulan = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30);

        $umur_bulan_bulat = floor($umur_bulan);

        /**
         * Protein DIIT
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_diit = 1.84 * $score->periksa->berat_badan;

        }else{

            $protein_diit =  1.79 * $score->periksa->berat_badan;
        }

        return $protein_diit;

        /**
         * Protein KKP
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_kkp = 2.05 * $score->periksa->berat_badan;

        }else{

            $protein_kkp =  2.03 * $score->periksa->berat_badan;
        }

        //return $protein_kkp;
        
        /**
         * Menghitung R 18
         */
        $z18 = $energi + (0.3 * $energi);

        $y18 = $protein_kkp;
        
    }

   /**
     * Rule 19
     * R19 min( Zbbu(giziburuk) &Ztbu(SangatPendek) & Zbbtb(Kurus) z19= energi+(0.4*energi) y19=kkp
     * ============================================
     */

    public function R_19($gizi_buruk = 0, $sangat_pendek = 0, $kurus = 0)
    {

        $score = Score::with('periksa')->where('id_periksa', 1)->with('dataBalita')->where('id_balita', 1)->first();


        // return $score->periksa->berat_badan;
        
        $umur_tahun = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30 *12);
        /**
         * Pembulatan umur ke tahun
         */
        $umur_tahun_bulat = floor($umur_tahun);

        /**
         * CARI ENERGI
         */
        if ($umur_tahun_bulat <= 1) {
            
            $energi = 110 * $score->periksa->berat_badan;  

        } else if ($umur_tahun_bulat <= 3) {

            $energi = 100 * $score->periksa->berat_badan;

        } else if ($umur_tahun_bulat <= 5) {

            $energi = 90 * $score->periksa->berat_badan;
        }

        // return $energi;

        /**
         * CARI PROTEIN
         */
        
        $umur_bulan = (strtotime($score->periksa->tgl_periksa) - strtotime($score->dataBalita->tgl_lahir)) / (60 * 60 * 24 * 30);

        $umur_bulan_bulat = floor($umur_bulan);

        /**
         * Protein DIIT
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_diit = 1.84 * $score->periksa->berat_badan;

        }else{

            $protein_diit =  1.79 * $score->periksa->berat_badan;
        }

        return $protein_diit;

        /**
         * Protein KKP
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_kkp = 2.05 * $score->periksa->berat_badan;

        }else{

            $protein_kkp =  2.03 * $score->periksa->berat_badan;
        }

        //return $protein_kkp;
        
        /**
         * Menghitung R 19
         */
        $z19 = $energi + (0.4 * $energi);

        $y19 = $protein_kkp;
        
    }		
}
