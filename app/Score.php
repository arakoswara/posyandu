<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $table = 'score';

    protected $fillable = ['id_balita', 'id_periksa', 'zbbu', 'ztbu', 'zbbtb', 'energi', 'protein'];

    public function dataBalita()
    {
    	return $this->belongsTo('App\DataBalita', 'id_balita');
    }

    public function periksa()
    {
    	return $this->belongsTo('App\Periksa', 'id_periksa');
    }
}
