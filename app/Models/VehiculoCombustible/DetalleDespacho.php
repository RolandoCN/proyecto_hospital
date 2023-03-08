<?php

namespace App\Models\VehiculoCombustible;

use Illuminate\Database\Eloquent\Model;

class DetalleDespacho extends Model
{
    protected $table = 'vc_detalle_despacho';
    protected $primaryKey  = 'iddetalle_despacho';
    public $timestamps = false;

    public function vehiculo(){
        return $this->belongsTo('App\Models\VehiculoCombustible\Vehiculo', 'id_vehiculo', 'id_vehiculo');
    }

    public function tipocombustible(){
        return $this->belongsTo('App\Models\VehiculoCombustible\TipoCombustible', 'id_tipocombustible', 'id_tipocombustible');
    }

    public function cabecera(){
        return $this->belongsTo('App\Models\VehiculoCombustible\CabeceraDespacho', 'idcabecera_despacho', 'idcabecera_despacho')->with('gasolinera');
    }

    public function chofer(){
        return $this->belongsTo('App\Models\Persona', 'idconductor', 'idpersona');
    }


}
?>