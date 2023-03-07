<?php

namespace App\Models\VehiculoCombustible;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    protected $table = 'vc_vehiculo';
    protected $primaryKey  = 'id_vehiculo';
    public $timestamps = false;

    public function tipoUso(){
        return $this->belongsTo('App\Models\VehiculoCombustible\TipoUso', 'id_tipouso', 'id_tipouso');
    }

    public function tipoMedicion(){
        return $this->belongsTo('App\Models\VehiculoCombustible\TipoMedicion', 'id_tipomedicion', 'id_tipomedicion');
    }

}
?>