<?php

namespace App\Models\VehiculoCombustible;

use Illuminate\Database\Eloquent\Model;

class Autoriza extends Model
{
    protected $table = 'vc_autorizado_salida';
    protected $primaryKey  = 'id_autorizado_salida';
    public $timestamps = false;


}
?>