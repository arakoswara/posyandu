<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SKDN extends Model
{
    protected $table = 'skdn';

    protected $fillable = ['s', 'k', 'd', 'n', 'date'];
}
