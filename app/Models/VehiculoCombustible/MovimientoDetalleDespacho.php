<?php

namespace App\Models\VehiculoCombustible;

use Illuminate\Database\Eloquent\Model;

class MovimientoDetalleDespacho extends Model
{
    protected $table = 'vc_movimiento_detalle_desp';
    protected $primaryKey  = 'id_movimiento_detalle_desp';
    public $timestamps = false;

    public function detalleMovimiento(){
        return $this->belongsTo('App\Models\VehiculoCombustible\Movimiento', 'id_movimiento', 'idmovimiento')
        ->where('estado','!=', 'Eliminada');
    }


}
?>