<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BBTB2 extends Model
{
    protected $table = 'bbtb2';

    protected $fillable = ['nobbtb', 'jk', 'tb', 'sdmin1', 'median', 'sdplus1'];
}
