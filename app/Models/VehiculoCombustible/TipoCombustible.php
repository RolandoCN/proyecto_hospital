<?php

namespace App\Models\VehiculoCombustible;

use Illuminate\Database\Eloquent\Model;

class TipoCombustible extends Model
{
    protected $table = 'vc_tipocombustible';
    protected $primaryKey  = 'id_tipocombustible';
    public $timestamps = false;

}
?>