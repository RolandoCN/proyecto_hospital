<?php

namespace App\Models\VehiculoCombustible;

use Illuminate\Database\Eloquent\Model;

class TareaDetalleDespacho extends Model
{
    protected $table = 'vc_tareas_detalle_desp';
    protected $primaryKey  = 'id_tareas_detalle_desp';
    public $timestamps = false;

    public function detalleTarea(){
        return $this->belongsTo('App\Models\VehiculoCombustible\Tarea', 'id_tarea', 'id_tarea')
        ->where('estado','!=', 'Eliminada');
    }


}
?>