<?php

namespace App\Models\VehiculoCombustible;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $table = 'vc_movimiento';
    protected $primaryKey  = 'idmovimiento';
    public $timestamps = false;


    public function vehiculo(){
        return $this->belongsTo('App\Models\VehiculoCombustible\Vehiculo', 'id_vehiculo', 'id_vehiculo');
    }


    public function chofer(){
        return $this->belongsTo('App\Models\Persona', 'id_chofer', 'idpersona');
    }

}
?>