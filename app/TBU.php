<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TBU extends Model
{
    protected $table = 'tbu';

    protected $fillable = ['notbu', 'jk', 'umur', 'sdmin1', 'median', 'sdplus1'];
}
