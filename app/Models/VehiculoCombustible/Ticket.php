<?php

namespace App\Models\VehiculoCombustible;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'vc_ticket';
    protected $primaryKey  = 'id';
    public $timestamps = false;

    public function vehiculo(){
        return $this->belongsTo('App\Models\VehiculoCombustible\Vehiculo', 'id_vehiculo', 'id_vehiculo');
    }

    public function gasolinera(){
        return $this->belongsTo('App\Models\VehiculoCombustible\Gasolinera', 'id_gasolinera', 'id_gasolinera');
    }


    public function chofer(){
        return $this->belongsTo('App\Models\Persona', 'idchofer', 'idpersona');
    }

  
    public function combustible(){
        return $this->belongsTo('App\Models\VehiculoCombustible\TipoCombustible', 'id_tipocombustible', 'id_tipocombustible');
    }
}