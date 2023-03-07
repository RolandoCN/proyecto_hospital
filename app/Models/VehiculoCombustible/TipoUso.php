<?php

namespace App\Models\VehiculoCombustible;

use Illuminate\Database\Eloquent\Model;

class TipoUso extends Model
{
    protected $table = 'vc_tipouso';
    protected $primaryKey  = 'id_tipouso';
    public $timestamps = false;

}
?>