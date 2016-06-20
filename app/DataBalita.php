<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataBalita extends Model
{
    protected $table = 'data_balita';

    protected $fillable = [
    	'no_reg', 'nama_balita', 'tgl_lahir', 'jenis_kelamin', 'nama_ayah', 'nama_ibu'
    ];

    public function periksas()
    {
    	return $this->hasOne('App\Periksa', 'id_balita');
    }

    public function score()
    {
    	return $this->hasMany('App\Score', 'id_balita');
    }

}
