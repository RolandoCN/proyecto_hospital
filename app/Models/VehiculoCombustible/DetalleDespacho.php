<?php

namespace App\Models\VehiculoCombustible;

use Illuminate\Database\Eloquent\Model;

class DetalleDespacho extends Model
{
    protected $table = 'vc_detalle_despacho';
    protected $primaryKey  = 'iddetalle_despacho';
    public $timestamps = false;

    public function vehiculo(){
        return $this->belongsTo('App\Models\VehiculoCombustible\Vehiculo', 'id_vehiculo', 'id_vehiculo')
        ->with('departamento');
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
   
    public function ticket(){
        return $this->belongsTo('App\Models\VehiculoCombustible\Ticket', 'num_factura_ticket', 'numero_ticket');
    }

    public function movimiento(){
        return $this->belongsTo('App\Models\VehiculoCombustible\Movimiento', 'num_factura_ticket', 'nro_ticket')->with('autoriza');
    }

    public function tareasDespacho(){
        return $this->hasMany('App\Models\Movimiento\TareaDetalleDespacho', 'iddetalle_despacho', 'iddetalle_despacho')->with('detalleTarea');
    }

    public function movimientosDespacho(){
        return $this->hasMany('App\Models\VehiculoCombustible\MovimientoDetalleDespacho', 'iddetalle_despacho', 'iddetalle_despacho')->with('detalleMovimiento');
    }


}
?>