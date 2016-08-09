<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TbGizi extends Model
{
    protected $table = "tb_gizi";

    protected $fillable = ['gizi_buruk', 'gizi_kurang', 'gizi_baik', 'gizi_lebih', 'month'];
}
