<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BBTB1 extends Model
{
    protected $table = 'bbtb1';

    protected $fillable = ['nobbtb', 'jk', 'tb', 'sdmin1', 'median', 'sdplus1'];
}
