<?php

namespace App\Models\VehiculoCombustible;

use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    protected $table = 'vc_tarea';
    protected $primaryKey  = 'id_tarea';
    public $timestamps = false;

    public function vehiculo(){
        return $this->belongsTo('App\Models\VehiculoCombustible\Vehiculo', 'id_vehiculo', 'id_vehiculo');
    }

    public function chofer(){
        return $this->belongsTo('App\Models\Persona', 'id_chofer', 'idpersona');
    }

}
?>