<?php

namespace App\Models\VehiculoCombustible;

use Illuminate\Database\Eloquent\Model;

class TipoMedicion extends Model
{
    protected $table = 'vc_tipomedicion';
    protected $primaryKey  = 'id_tipomedicion';
    public $timestamps = false;

}
?>