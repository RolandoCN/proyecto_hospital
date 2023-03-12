<?php

namespace App\Models\VehiculoCombustible;

use Illuminate\Database\Eloquent\Model;

class TareaMovimento extends Model
{
    protected $table = 'vc_tarea_veh_movim';
    protected $primaryKey  = 'id_tarea_veh_movim';
    public $timestamps = false;

}
?>