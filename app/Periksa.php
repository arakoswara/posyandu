<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Periksa extends Model
{
    protected $table = 'periksa';

    protected $fillable = [
    	'id_balita', 'tgl_periksa', 'berat_badan', 'tinggi_badan'
    ];

    public function dataBalita()
    {
    	return $this->belongsTo('App\DataBalita', 'id_balita');
    }
}