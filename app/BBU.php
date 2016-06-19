<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BBU extends Model
{
    protected $table = 'bbu';

    protected $fillable = ['nobbu', 'jk', 'umur', 'sdmin1', 'median', 'sdplus1'];
}
