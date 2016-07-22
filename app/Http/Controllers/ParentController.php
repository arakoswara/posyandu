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

        // return $data_periksa;

        $zbbuGiziburuk = $this->zbbuGiziburuk($data_periksa);
        $zbbuGizikurang     = $this->zbbuGizikurang($data_periksa);
        $zbbuGizibaik       = $this->zbbuGizibaik($data_periksa);
        $zbbuGizilebih      = $this->zbbuGizilebih($data_periksa);
        /**
         * ztbu
         */
        $ztbuSangatPendek   = $this->ztbuSangatPendek($data_periksa);
        $ztbuPendek         = $this->ztbuPendek($data_periksa);
        $ztbuNormal         = $this->ztbuNormal($data_periksa);
        $ztbuTinggi         = $this->ztbuTinggi($data_periksa);
        /**
         * zbbtb
         */
        $zbbtbSangatKurus   = $this->zbbtbSangatKurus($data_periksa);
        $zbbtbKurus         = $this->zbbtbKurus($data_periksa);
        $zbbtbNormal        = $this->zbbtbNormal($data_periksa);
        $zbbtbGemuk         = $this->zbbtbGemuk($data_periksa);
        /**
         * Cari Energi & Protein
         */
        $CariEnergi         = $this->CariEnergi($data_periksa);
        $CariProteinDIIT    = $this->CariProteinDIIT($data_periksa);
        $CariProteinKKP     = $this->CariProteinKKP($data_periksa);

        $r_1 = $this->R_1($zbbuGizilebih, $ztbuTinggi, $zbbtbNormal, $CariEnergi, $CariProteinDIIT);

        $r_2 = $this->R_2($zbbuGizilebih, $ztbuNormal, $zbbtbGemuk, $CariEnergi, $CariProteinDIIT, $CariProteinKKP);

        $r_3 = $this->R_3($zbbuGizilebih, $ztbuPendek, $zbbtbGemuk, $CariEnergi, $CariProteinDIIT, $CariProteinKKP);

        $r_4 = $this->R_4($zbbuGizilebih, $ztbuSangatPendek, $zbbtbGemuk, $CariEnergi, $CariProteinDIIT, $CariProteinKKP);

        $r_5 = $this->R_5($zbbuGizibaik, $ztbuTinggi, $zbbtbNormal, $CariEnergi, $CariProteinDIIT, $CariProteinKKP);

        $r_6 = $this->R_6($zbbuGizibaik, $ztbuNormal, $zbbtbNormal, $CariEnergi, $CariProteinDIIT, $CariProteinKKP);

        $r_7 = $this->R_7($zbbuGizibaik, $ztbuPendek, $zbbtbNormal, $CariEnergi, $CariProteinDIIT, $CariProteinKKP);

        $r_8 = $this->R_8($zbbuGizibaik, $ztbuSangatPendek, $zbbtbGemuk, $CariEnergi, $CariProteinDIIT, $CariProteinKKP);

        $r_9 = $this->R_9($zbbuGizibaik, $ztbuSangatPendek, $zbbtbNormal, $CariEnergi, $CariProteinDIIT, $CariProteinKKP);

        $r_10 = $this->R_10($zbbuGizikurang, $ztbuTinggi, $zbbtbSangatKurus, $CariEnergi, $CariProteinDIIT, $CariProteinKKP);

        $r_11 = $this->R_11($zbbuGizikurang, $ztbuNormal, $zbbtbKurus, $CariEnergi, $CariProteinDIIT, $CariProteinKKP);

        $r_12 = $this->R_12($zbbuGizikurang, $ztbuPendek, $zbbtbNormal, $CariEnergi, $CariProteinDIIT, $CariProteinKKP);

        $r_13 = $this->R_13($zbbuGizikurang, $ztbuPendek, $zbbtbKurus, $CariEnergi, $CariProteinDIIT, $CariProteinKKP);

        $r_14 = $this->R_14($zbbuGizikurang, $ztbuSangatPendek, $zbbtbNormal, $CariEnergi, $CariProteinDIIT, $CariProteinKKP);

        $r_15 = $this->R_15($zbbuGiziburuk, $ztbuTinggi, $zbbtbSangatKurus, $CariEnergi, $CariProteinDIIT, $CariProteinKKP);

        $r_16 = $this->R_16($zbbuGiziburuk, $ztbuNormal, $zbbtbKurus, $CariEnergi, $CariProteinDIIT, $CariProteinKKP);

        $r_17 = $this->R_17($zbbuGiziburuk, $ztbuPendek, $zbbtbKurus, $CariEnergi, $CariProteinDIIT, $CariProteinKKP);

        $r_18 = $this->R_18($zbbuGiziburuk, $ztbuSangatPendek, $zbbtbNormal, $CariEnergi, $CariProteinDIIT, $CariProteinKKP);

        $r_19 = $this->R_19($zbbuGiziburuk, $ztbuSangatPendek, $zbbtbKurus, $CariEnergi, $CariProteinDIIT, $CariProteinKKP);

        $protein_energi = $this->sum_R($r_1, $r_2, $r_3, $r_4, $r_5, $r_6, $r_7, $r_8, $r_9, $r_10, $r_11, $r_12, $r_13, $r_14, $r_15, $r_16, $r_17, $r_18, $r_19, $data_periksa);

        return view('home.parent.hasil-periksa', compact('data_periksa', 'data_balita', 'protein_energi'));
    }

    /**
     * ZBBU
     */

    public function zbbuGiziburuk($data_periksa)
    {        

        $score = $data_periksa;

        if ($score['zbbu'] <= -3) {
            
            $gizi_buruk = 1;

        }else if($score['zbbu'] >= -3 && $score['zbbu'] <= -2) {

            $gizi_buruk = (-2 - $score['zbbu']);

        }else if($score['zbbu'] >= -2) {

            $gizi_buruk = 0;

        }

        return $gizi_buruk;

    }

    public function zbbuGizikurang($data_periksa)
    {

        $score = $data_periksa;        

        if ($score['zbbu'] <= -3 || $score['zbbu'] >= 0) {
            
             $gizi_kurang = 0;

        }else if($score['zbbu'] >= -3 && $score['zbbu'] <= -2) {

             $gizi_kurang = $score['zbbu'] + 3;

        }else if($score['zbbu'] >= -2 && $score['zbbu'] <= 0) {

             $gizi_kurang = (-$score['zbbu'])/2;

        }else if($score['zbbu'] == -2) {

             $gizi_kurang = 1;
        }
        return $gizi_kurang;

    }

    public function zbbuGizibaik($data_periksa)
    {
        $score = $data_periksa;

        if ($score['zbbu'] <= -2 || $score['zbbu'] >= 2) {
            
            $gizi_baik = 0;

        }else if($score['zbbu'] >= -2 && $score['zbbu'] <= 0) {

            $gizi_baik = ($score['zbbu'] + 2)/2;

        }else if($score['zbbu'] >= 0 && $score['zbbu'] <= 2) {

            $gizi_baik = (2 - $score['zbbu'])/2;

        }else if($score['zbbu'] == 0) {

            $gizi_baik = 1;
        }
        return $gizi_baik;

    }

    public function zbbuGizilebih($data_periksa)
    {
        $score = $data_periksa;

        if ($score['zbbu'] >= 0 && $score['zbbu'] <= 2) {
            
            $gizi_lebih = ($score['zbbu'])/2;

        }else if($score['zbbu'] >= 2) {

            $gizi_lebih = 1;

        }else if($score['zbbu'] <= 0) {

            $gizi_lebih = 0;
        }

        return $gizi_lebih;

    }

    /**
     * ZTBU
     */
    public function ztbuSangatPendek($data_periksa)
    {
        $score = $data_periksa;

        if ($score['ztbu'] <= -3) {
            
            $sangat_pendek = 1;

        }else if($score['ztbu'] >= -3 && $score['ztbu'] <= -2) {

            $sangat_pendek = (-2 - $score['ztbu']);

        }else if($score['ztbu'] >= -2) {

            $sangat_pendek = 0;

        }
        return $sangat_pendek;

    }

    public function ztbuPendek($data_periksa)
    {
        $score = $data_periksa;

        if ($score['ztbu'] <= -3 || $score['ztbu'] >= 0) {
            
            $pendek = 0;

        }else if($score['ztbu'] >= -3 && $score['ztbu'] <= -2) {

            $pendek = $score['ztbu'] + 3;

        }else if($score['ztbu'] >= -2 && $score['ztbu'] <= 0) {

            $pendek = (-$score['ztbu'])/2;

        }else if($score['ztbu'] == -2) {

            $pendek = 1;
        }
        return $pendek;

    }
    
    public function ztbuNormal($data_periksa)
    {
        $score = $data_periksa;

        if ($score['ztbu'] <= -2 || $score['ztbu'] >= 2) {
            
            $normal_ztbu = 0;

        }else if($score['ztbu'] >= -2 && $score['ztbu'] <= 0) {

            $normal_ztbu = ($score['ztbu'] + 2)/2;

        }else if($score['ztbu'] >= 0 && $score['ztbu'] <= 2) {

            $normal_ztbu = (2 - $score['ztbu'])/2;

        }else if($score['ztbu'] == 0) {

            $normal_ztbu = 1;
        }
        return $normal_ztbu;

    }

    public function ztbuTinggi($data_periksa)
    {
        $score = $data_periksa;

        if ($score['ztbu'] >= 0 && $score['ztbu'] <= 2) {
            
            $tinggi = ($score['ztbu'])/2;

        }else if($score['ztbu'] >= 2) {

            $tinggi = 1;

        }else if($score['ztbu'] <= 0) {

            $tinggi = 0;
        }

        return $tinggi;

    }

    /**
     * ZBBTB
     * ============================================
     */
    public function zbbtbSangatKurus($data_periksa)
    {
        $score = $data_periksa;

        if ($score['zbbtb'] <= -3) {
            
            $sangat_kurus = 1;

        }else if($score['zbbtb'] >= -3 && $score['zbbtb'] <= -2) {

            $sangat_kurus = (-2 - $score['zbbtb']);

        }else if($score['zbbtb'] >= -2) {

            $sangat_kurus = 0;

        }
        return $sangat_kurus;

    }

    public function zbbtbKurus($data_periksa)
    {
        $score = $data_periksa;

        if ($score['zbbtb'] <= -3 || $score['zbbtb'] >= 0) {
            
            $kurus = 0;

        }else if($score['zbbtb'] >= -3 && $score['zbbtb'] <= -2) {

            $kurus = $score['zbbtb'] + 3;

        }else if($score['zbbtb'] >= -2 && $score['zbbtb'] <= 0) {

            $kurus = (-$score['zbbtb'])/2;

        }else if($score['zbbtb'] == -2) {

            $kurus = 1;
        }
        return $kurus;

    }

    public function zbbtbNormal($data_periksa)
    {
        $score = $data_periksa;

        if ($score['zbbtb'] <= -2 || $score['zbbtb'] >= 2) {
            
            $normal_zbbtb = 0;

        }else if($score['zbbtb'] >= -2 && $score['zbbtb'] <= 0) {

            $normal_zbbtb = ($score['zbbtb'] + 2)/2;

        }else if($score['zbbtb'] >= 0 && $score['zbbtb'] <= 2) {

            $normal_zbbtb = (2 - $score['zbbtb'])/2;

        }else if($score['zbbtb'] == 0) {

            $normal_zbbtb = 1;
        }

        return $normal_zbbtb;

    }

    public function zbbtbGemuk($data_periksa)
    {
        $score = $data_periksa;

        if ($score['zbbtb'] >= 0 && $score['zbbtb'] <= 2) {
            
            $gemuk = ($score['zbbtb'])/2;

        }else if($score['zbbtb'] >= 2) {

            $gemuk = 1;

        }else if($score['zbbtb'] <= 0) {

            $gemuk = 0;
        }

        return $gemuk;

    }

    /**
     * Cari energi 
     */
    public function CariEnergi($data_periksa)
    {
        $data_balita = DataBalita::where('id', $data_periksa['id_balita'])->first();

        $score = $data_periksa;
        
        $umur_tahun = (strtotime($score['tgl_periksa']) - strtotime($data_balita->tgl_lahir)) / (60 * 60 * 24 * 30 *12);
        /**
         * Pembulatan umur dalam tahun
         */
        $umur_tahun_bulat = floor($umur_tahun);

        /**
         * CARI ENERGI
         */
        if ($umur_tahun_bulat <= 1) {
            
            $energi = 110 * $score['berat_badan'];  

        } else if ($umur_tahun_bulat <= 3) {

            $energi = 100 * $score['berat_badan'];

        } else if ($umur_tahun_bulat <= 5) {

            $energi = 90 * $score['berat_badan'];
        }

        return $energi;
    }

    public function CariProteinDIIT($data_periksa)
    {
        $score = $data_periksa;

        $data_balita = DataBalita::where('id', $data_periksa['id_balita'])->first();

        $umur_bulan = (strtotime($score['tgl_periksa']) - strtotime($data_balita->tgl_lahir)) / (60 * 60 * 24 * 30);

        $umur_bulan_bulat = floor($umur_bulan);

        /**
         * Protein DIIT
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_diit = 1.84 * $score['berat_badan'];

        }else{

            $protein_diit =  1.79 * $score['berat_badan'];
        }

        return $protein_diit;
    }

    public function CariProteinKKP($data_periksa)
    {
        $score = $data_periksa;

        $data_balita = DataBalita::where('id', $data_periksa['id_balita'])->first();

        $umur_bulan = (strtotime($score['tgl_periksa']) - strtotime($data_balita->tgl_lahir)) / (60 * 60 * 24 * 30);

        $umur_bulan_bulat = floor($umur_bulan);

        /**
         * Protein KKP
         */
        if ($umur_bulan_bulat <= 48) {
            
            $protein_kkp = 2.05 * $score['berat_badan'];

        }else{

            $protein_kkp =  2.03 * $score['berat_badan'];
        }

        return $protein_kkp;
    }

    /**
     * Rule 1
     * R1 Zbbu(gizilebih) &Ztbu(Tinggi) & Zbbtb(Normal) z1= energi-(0.2*energi)  y1=diit
     * ============================================
     */ 
    public function R_1($zbbuGizilebih, $ztbuTinggi, $zbbtbNormal, $CariEnergi, $CariProteinDIIT)
    {
        /**
         * cari nilai minimum R1
         */
        $r_1 = min($zbbuGizilebih, $ztbuTinggi, $zbbtbNormal);

        /**
         * Konsekuen z1 dan y1
         */
        $z1 = $CariEnergi - (0.2 * $CariEnergi);

        $y1 = $CariProteinDIIT;

        $rz1 = $r_1 * $z1;

        $ry1= $r_1 * $y1;

        return $data_R1 = array(
            'r_1' => $r_1,

            'ry1' => $ry1,

            'rz1' => $rz1
        );
        

    }

    /**
     * Rule 2
     * R2 Zbbu(gizilebih) &Ztbu(Normal)  & Zbbtb(Gemuk) z2= energi-(0.1*energi) y2=diit
     * ============================================
     */
    
    public function R_2($zbbuGizilebih, $ztbuNormal, $zbbtbGemuk, $CariEnergi, $CariProteinDIIT, $CariProteinKKP)
    {
        /**
         * cari nilai minimum r2
         */
        $r_2 = min($zbbuGizilebih, $ztbuNormal, $zbbtbGemuk);

        /**
         * Konsekuen z2 dan y2
         */
        $z2 = $CariEnergi - (0.1 * $CariEnergi);

        $y2 = $CariProteinDIIT;

        $rz2 = $r_2 * $z2;

        $ry2= $r_2 * $y2;

        return $data_R2 = array(
            'r_2' => $r_2,

            'ry2' => $ry2,

            'rz2' => $rz2
        );
    }

    /**
     * Rule 3
     * R3 Zbbu(gizilebih) &Ztbu(Pendek) & Zbbtb(Gemuk) z3=energi-(0.1*energi) y3=diit
     * ============================================
     */

    public function R_3($zbbuGizilebih, $ztbuPendek, $zbbtbGemuk, $CariEnergi, $CariProteinDIIT, $CariProteinKKP)
    {
        /**
         * cari nilai minimum r3
         */
        $r_3 = min($zbbuGizilebih, $ztbuPendek, $zbbtbGemuk);

        /**
         * Konsekuen z3 dan y3
         */
        $z3 = $CariEnergi - (0.1 * $CariEnergi);

        $y3 = $CariProteinDIIT;

        $rz3 = $r_3 * $z3;

        $ry3= $r_3 * $y3;

        return $data_R3 = array(
            'r_3' => $r_3,

            'ry3' => $ry3,

            'rz3' => $rz3
        );
        
    }
    /**
     * Rule 4
     * R4 Zbbu(gizilebih) &Ztbu(SangatPendek) & Zbbtb(Gemuk) z4=energi-(0.2*energi) y4=diit
     * ============================================
     */

    public function R_4($zbbuGizilebih, $ztbuSangatPendek, $zbbtbGemuk, $CariEnergi, $CariProteinDIIT, $CariProteinKKP)
    {
        /**
         * cari nilai minimum r4
         */
        $r_4 = min($zbbuGizilebih, $ztbuSangatPendek, $zbbtbGemuk);

        /**
         * Konsekuen z4 dan y4
         */
        $z4 = $CariEnergi - (0.2 * $CariEnergi);

        $y4 = $CariProteinDIIT;

        $rz4 = $r_4 * $z4;

        $ry4= $r_4 * $y4;

        return $data_R4 = array(
            'r_4' => $r_4,

            'ry4' => $ry4,

            'rz4' => $rz4
        );
    }

    /**
     * Rule 5
     * R5 Zbbu(gizibaik) &Ztbu(Tinggi) & Zbbtb(Normal) z5= energi y5=diit
     * ============================================
     */

    public function R_5($zbbuGizibaik, $ztbuTinggi, $zbbtbNormal, $CariEnergi, $CariProteinDIIT, $CariProteinKKP)
    {
        /**
         * cari nilai minimum r5
         */
        $r_5 = min($zbbuGizibaik, $ztbuTinggi, $zbbtbNormal);

        /**
         * Konsekuen z5 dan y5
         */
        $z5 = $CariEnergi;

        $y5 = $CariProteinDIIT;

        $rz5 = $r_5 * $z5;

        $ry5= $r_5 * $y5;

        return $data_R5 = array(
            'r_5' => $r_5,

            'ry5' => $ry5,

            'rz5' => $rz5
        );
       
    }  

    /**
     * Rule 6
     * R6 min( Zbbu(gizibaik) &Ztbu(Normal) & Zbbtb(Normal) z6= energi y6=diit
     * ============================================
     */

    public function R_6($zbbuGizibaik, $ztbuNormal, $zbbtbNormal, $CariEnergi, $CariProteinDIIT, $CariProteinKKP)
    {
        /**
         * cari nilai minimum r6
         */
        $r_6 = min($zbbuGizibaik, $ztbuNormal, $zbbtbNormal);

        /**
         * Konsekuen z6 dan y6
         */
        $z6 = $CariEnergi;

        $y6 = $CariProteinDIIT;

        $rz6 = $r_6 * $z6;

        $ry6= $r_6 * $y6;

        return $data_R6 = array(
            'r_6' => $r_6,

            'ry6' => $ry6,

            'rz6' => $rz6
        );

    }

    /**
     * Rule 7
     * R7 min( Zbbu(gizibaik) &Ztbu(Pendek) & Zbbtb(Normal) z7= energi y7=diit
     * ============================================
     */

    public function R_7($zbbuGizibaik, $ztbuPendek, $zbbtbNormal, $CariEnergi, $CariProteinDIIT, $CariProteinKKP)
    {
        /**
         * cari nilai minimum r7
         */
        $r_7 = min($zbbuGizibaik, $ztbuPendek, $zbbtbNormal);

        /**
         * Konsekuen z7 dan y7
         */
        $z7 = $CariEnergi;

        $y7 = $CariProteinDIIT;

        $rz7 = $r_7 * $z7;

        $ry7= $r_7 * $y7;

        return $data_R7 = array(
            'r_7' => $r_7,

            'ry7' => $ry7,

            'rz7' => $rz7
        );
        
    }

    /**
     * Rule 8
     * R8 min( Zbbu(gizibaik) &Ztbu(SangatPendek) & Zbbtb(Gemuk) z8= energi y8=diit
     * ============================================
     */

    public function R_8($zbbuGizibaik, $ztbuSangatPendek, $zbbtbGemuk, $CariEnergi, $CariProteinDIIT, $CariProteinKKP)
    {
        /**
         * cari nilai minimum r8
         */
        $r_8 = min($zbbuGizibaik, $ztbuSangatPendek, $zbbtbGemuk);

        /**
         * Konsekuen z8 dan y8
         */
        $z8 = $CariEnergi;

        $y8 = $CariProteinDIIT;

        $rz8 = $r_8 * $z8;

        $ry8= $r_8 * $y8;

        return $data_R8 = array(
            'r_8' => $r_8,

            'ry8' => $ry8,

            'rz8' => $rz8
        );
 
    }

    /**
     * Rule 9
     * R9 min( Zbbu(gizibaik) &Ztbu(SangatPendek) & Zbbtb(Normal) z9= energi y9=diit
     * ============================================
     */

    public function R_9($zbbuGizibaik, $ztbuSangatPendek, $zbbtbNormal, $CariEnergi, $CariProteinDIIT, $CariProteinKKP)
    {
       /**
         * cari nilai minimum r9
         */
        $r_9 = min($zbbuGizibaik, $ztbuSangatPendek, $zbbtbNormal);

        /**
         * Konsekuen z9 dan y9
         */
        $z9 = $CariEnergi;

        $y9 = $CariProteinDIIT;

        $rz9 = $r_9 * $z9;

        $ry9= $r_9 * $y9;

        return $data_R9 = array(
            'r_9' => $r_9,

            'ry9' => $ry9,

            'rz9' => $rz9
        );
        
    }

    /**
     * Rule 10
     * R10 min( Zbbu(gizikurang) &Ztbu(Tinggi) & Zbbtb(Sangat Kurus) z10 = energi+(0.4*energi) y10=diit
     * ============================================
     */

    public function R_10($zbbuGizikurang, $ztbuTinggi, $zbbtbSangatKurus, $CariEnergi, $CariProteinDIIT, $CariProteinKKP)
    {
        /**
         * cari nilai minimum r10
         */
        $r_10 = min($zbbuGizikurang, $ztbuTinggi, $zbbtbSangatKurus);

        /**
         * Konsekuen z10 dan y10
         */
        $z10 = $CariEnergi + (0.4 * $CariEnergi);

        $y10 = $CariProteinDIIT;

        $rz10 = $r_10 * $z10;

        $ry10= $r_10 * $y10;

        return $data_R10 = array(
            'r_10' => $r_10,

            'ry10' => $ry10,

            'rz10' => $rz10
        );

    }

    /**
     * Rule 11
     * R11 min( Zbbu(gizikurang) &Ztbu(Normal) & Zbbtb(Kurus) z11= energi+(0.2*energi) y11=diit
     * ============================================
     */

    public function R_11($zbbuGizikurang, $ztbuNormal, $zbbtbKurus, $CariEnergi, $CariProteinDIIT, $CariProteinKKP)
    {
        /**
         * cari nilai minimum r11
         */
        $r_11 = min($zbbuGizikurang, $ztbuNormal, $zbbtbKurus);

        /**
         * Konsekuen z11 dan y11
         */
        $z11 = $CariEnergi + (0.2 * $CariEnergi);

        $y11 = $CariProteinDIIT;

        $rz11 = $r_11 * $z11;

        $ry11= $r_11 * $y11;

        return $data_R11 = array(
            'r_11' => $r_11,

            'ry11' => $ry11,

            'rz11' => $rz11
        );
        
    }

    /**
     * Rule 12
     * R12 min( Zbbu(gizikurang) &Ztbu(Pendek) & Zbbtb(Normal) z12 = energi+(0.2*energi) y12=diit
     * ============================================
     */

    public function R_12($zbbuGizikurang, $ztbuPendek, $zbbtbNormal, $CariEnergi, $CariProteinDIIT, $CariProteinKKP)
    {
        /**
         * cari nilai minimum r12
         */
        $r_12 = min($zbbuGizikurang, $ztbuPendek, $zbbtbNormal);

        /**
         * Konsekuen z12 dan y12
         */
        $z12 = $CariEnergi + (0.2 * $CariEnergi);

        $y12 = $CariProteinDIIT;

        $rz12 = $r_12 * $z12;

        $ry12= $r_12 * $y12;

        return $data_R12 = array(
            'r_12' => $r_12,

            'ry12' => $ry12,

            'rz12' => $rz12
        );

    }

    /**
     * Rule 13
     * R13 min( Zbbu(gizikurang) &Ztbu(Pendek) & Zbbtb(Kurus) z13= energi+(0.3*energi) y13=diit
     * ============================================
     */

    public function R_13($zbbuGizikurang, $ztbuPendek, $zbbtbKurus, $CariEnergi, $CariProteinDIIT, $CariProteinKKP)
    {
        /**
         * cari nilai minimum r13
         */
        $r_13 = min($zbbuGizikurang, $ztbuPendek, $zbbtbKurus);

        /**
         * Konsekuen z13 dan y13
         */
        $z13 = $CariEnergi + (0.3 * $CariEnergi);

        $y13 = $CariProteinDIIT;

        $rz13 = $r_13 * $z13;

        $ry13= $r_13 * $y13;

        return $data_R13 = array(
            'r_13' => $r_13,

            'ry13' => $ry13,

            'rz13' => $rz13
        );
        
    }

    /**
     * Rule 14
     * R14 min( Zbbu(gizikurang) &Ztbu(SangatPendek) & Zbbtb(Normal) z14 =energi+(0.3*energi) y14=diit
     * ============================================
     */

    public function R_14($zbbuGizikurang, $ztbuSangatPendek, $zbbtbNormal, $CariEnergi, $CariProteinDIIT, $CariProteinKKP)
    {
        /**
         * cari nilai minimum r14
         */
        $r_14 = min($zbbuGizikurang, $ztbuSangatPendek, $zbbtbNormal);

        /**
         * Konsekuen z14 dan y14
         */
        $z14 = $CariEnergi + (0.3 * $CariEnergi);

        $y14 = $CariProteinDIIT;

        $rz14 = $r_14 * $z14;

        $ry14= $r_14 * $y14;

        return $data_R14 = array(
            'r_14' => $r_14,

            'ry14' => $ry14,

            'rz14' => $rz14
        );
        
    }

    /**
     * Rule 15
     * R15 min( Zbbu(giziburuk) &Ztbu(Tinggi) & Zbbtb(SangatKurus) z15= energi+(0.4*energi) y15=kkp
     * ============================================
     */

    public function R_15($zbbuGiziburuk, $ztbuTinggi, $zbbtbSangatKurus, $CariEnergi, $CariProteinDIIT, $CariProteinKKP)
    {
       /**
         * cari nilai minimum r15
         */
        $r_15 = min($zbbuGiziburuk, $ztbuTinggi, $zbbtbSangatKurus);

        /**
         * Konsekuen z15 dan y15
         */
        $z15 = $CariEnergi + (0.4 * $CariEnergi);

        $y15 = $CariProteinKKP;

        $rz15 = $r_15 * $z15;

        $ry15= $r_15 * $y15;

        return $data_R15 = array(
            'r_15' => $r_15,

            'ry15' => $ry15,

            'rz15' => $rz15
        );

    }

    /**
     * Rule 16
     * R16 min( Zbbu(giziburuk) &Ztbu(Normal) & Zbbtb(Kurus) z16 = energi+(0.3*energi) y16=kkp
     * ============================================
     */

    public function R_16($zbbuGiziburuk, $ztbuNormal, $zbbtbKurus, $CariEnergi, $CariProteinDIIT, $CariProteinKKP)
    {
        /**
         * cari nilai minimum r16
         */
        $r_16 = min($zbbuGiziburuk, $ztbuNormal, $zbbtbKurus);

        /**
         * Konsekuen z16 dan y16
         */
        $z16 = $CariEnergi + (0.3 * $CariEnergi);

        $y16 = $CariProteinKKP;

        $rz16 = $r_16 * $z16;

        $ry16= $r_16 * $y16;

        return $data_R16 = array(
            'r_16' => $r_16,

            'ry16' => $ry16,

            'rz16' => $rz16
        );
    }

    /**
     * Rule 17
     * R17 min( Zbbu(giziburuk) &Ztbu(Pendek) & Zbbtb(Kurus) z17= energi+(0.4*energi) y17=kkp
     * ============================================
     */

    public function R_17($zbbuGiziburuk, $ztbuPendek, $zbbtbKurus, $CariEnergi, $CariProteinDIIT, $CariProteinKKP)
    {
        /**
         * cari nilai minimum r17
         */
        $r_17 = min($zbbuGiziburuk, $ztbuPendek, $zbbtbKurus);

        /**
         * Konsekuen z17 dan y17
         */
        $z17 = $CariEnergi + (0.4 * $CariEnergi);

        $y17 = $CariProteinKKP;

        $rz17 = $r_17 * $z17;

        $ry17= $r_17 * $y17;

        return $data_R17 = array(
            'r_17' => $r_17,

            'ry17' => $ry17,

            'rz17' => $rz17
        );
        
    }

    /**
     * Rule 18
     * R18 min( Zbbu(giziburuk) &Ztbu(SangatPendek) & Zbbtb(Normal) z18= energi+(0.3*energi) y18=kkp
     * ============================================
     */

    public function R_18($zbbuGiziburuk, $ztbuSangatPendek, $zbbtbNormal, $CariEnergi, $CariProteinDIIT, $CariProteinKKP)
    {
        /**
         * cari nilai minimum r18
         */
        $r_18 = min($zbbuGiziburuk, $ztbuSangatPendek, $zbbtbNormal);

        /**
         * Konsekuen z18 dan y18
         */
        $z18 = $CariEnergi + (0.3 * $CariEnergi);

        $y18 = $CariProteinKKP;

        $rz18 = $r_18 * $z18;

        $ry18= $r_18 * $y18;

        return $data_R18 = array(
            'r_18' => $r_18,

            'ry18' => $ry18,

            'rz18' => $rz18
        );

    }

   /**
     * Rule 19
     * R19 min( Zbbu(giziburuk) &Ztbu(SangatPendek) & Zbbtb(Kurus) z19= energi+(0.4*energi) y19=kkp
     * ============================================
     */

    public function R_19($zbbuGiziburuk, $ztbuSangatPendek, $zbbtbKurus, $CariEnergi, $CariProteinDIIT, $CariProteinKKP)
    {
        /**
         * cari nilai minimum r19
         */
        $r_19 = min($zbbuGiziburuk, $ztbuSangatPendek, $zbbtbKurus);

        /**
         * Konsekuen z19 dan y19
         */
        $z19 = $CariEnergi + (0.4 * $CariEnergi);

        $y19 = $CariProteinKKP;

        $rz19 = $r_19 * $z19;

        $ry19 = $r_19 * $y19;

        return $data_R19 = array(
            'r_19' => $r_19,

            'ry19' => $ry19,

            'rz19' => $rz19
        );
    }

    public function sum_R($r_1, $r_2, $r_3, $r_4, $r_5, $r_6, $r_7, $r_8, $r_9, $r_10, $r_11, $r_12, $r_13, $r_14, $r_15, $r_16, $r_17, $r_18, $r_19, $periksa_balita)
    {
        $data_1     = $r_1;
        $data_2     = $r_2;
        $data_3     = $r_3;
        $data_4     = $r_4;
        $data_5     = $r_5;
        $data_6     = $r_6;
        $data_7     = $r_7;
        $data_8     = $r_8;
        $data_9     = $r_9;
        $data_10    = $r_10;
        $data_11    = $r_11;
        $data_12    = $r_12;
        $data_13    = $r_13;
        $data_14    = $r_14;
        $data_15    = $r_15;
        $data_16    = $r_16;
        $data_17    = $r_17;
        $data_18    = $r_18;
        $data_19    = $r_19;

        /**
         * Penjumlahan R
         */
        $jumlah_R = ($data_1['r_1'] + $data_2['r_2'] + $data_3['r_3'] + $data_4['r_4'] + $data_5['r_5'] + $data_6['r_6'] +$data_7['r_7'] + $data_8['r_8'] + $data_9['r_9'] +$data_10['r_10'] + $data_11['r_11'] + $data_12['r_12'] + $data_13['r_13'] + $data_14['r_14'] + $data_15['r_15'] + $data_16['r_16'] + $data_17['r_17'] + $data_18['r_18'] + $data_19['r_19']);

        /**
         * Penjumlahan RZ
         */
        $jumlah_RZ = ($data_1['rz1'] + $data_2['rz2'] + $data_3['rz3'] + $data_4['rz4'] + $data_5['rz5'] + $data_6['rz6'] +$data_7['rz7'] + $data_8['rz8'] + $data_9['rz9'] +$data_10['rz10'] + $data_11['rz11'] + $data_12['rz12'] + $data_13['rz13'] + $data_14['rz14'] + $data_15['rz15'] + $data_16['rz16'] + $data_17['rz17'] + $data_18['rz18'] + $data_19['rz19']);

        /**
        * Penjumlahan RY
        */
        $jumlah_RY = ($data_1['ry1'] + $data_2['ry2'] + $data_3['ry3'] + $data_4['ry4'] + $data_5['ry5'] + $data_6['ry6'] +$data_7['ry7'] + $data_8['ry8'] + $data_9['ry9'] +$data_10['ry10'] + $data_11['ry11'] + $data_12['ry12'] + $data_13['ry13'] + $data_14['ry14'] + $data_15['ry15'] + $data_16['ry16'] + $data_17['ry17'] + $data_18['ry18'] + $data_19['ry19']);
        /**
        * Hasil akhir Energi
        */
        $hasil_energi = $jumlah_RZ / $jumlah_R;
        /**
        * Hasil akhir Protein
        */
        $hasil_protein = $jumlah_RY / $jumlah_R;

        $sum_R = [
            'energi' => $hasil_energi,

            'protein' => $hasil_protein
        ];

        return $sum_R;

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

    public function getPDFPeriksa(Request $request)
    {
        $data = $request->all();

        $pdf = PDF::loadView('home.parent.test', compact('data'));

        return $pdf->stream('semua-riwayat.pdf');
    }
    
}
