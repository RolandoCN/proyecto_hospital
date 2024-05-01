<?php

namespace App\Models\VehiculoCombustible;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $table = 'vc_movimiento';
    protected $primaryKey  = 'idmovimiento';
    public $timestamps = false;


    public function vehiculo(){
        return $this->belongsTo('App\Models\VehiculoCombustible\Vehiculo', 'id_vehiculo', 'id_vehiculo')
        ->with('marca');
    }

    public function area(){
        return $this->belongsTo('App\Models\VehiculoCombustible\Area', 'id_area_solicita', 'id_area');
    }

    public function chofer(){
        return $this->belongsTo('App\Models\Persona', 'id_chofer', 'idpersona');
    }

    public function ticket(){
        return $this->belongsTo('App\Models\VehiculoCombustible\Ticket', 'nro_ticket', 'numero_ticket')->where('estado','A');
    }

    public function autoriza(){
        return $this->belongsTo('App\Models\VehiculoCombustible\Autoriza', 'id_autorizado_salida', 'id_autorizado_salida');
    }

}
?>