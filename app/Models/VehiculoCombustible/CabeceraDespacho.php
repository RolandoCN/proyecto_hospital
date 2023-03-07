<?php

namespace App\Models\VehiculoCombustible;

use Illuminate\Database\Eloquent\Model;

class CabeceraDespacho extends Model
{
    protected $table = 'vc_cabecera_despacho';
    protected $primaryKey  = 'idcabecera_despacho';
    public $timestamps = false;


    public function gasolinera(){
        return $this->belongsTo('App\Models\VehiculoCombustible\Gasolinera', 'id_gasolinera', 'id_gasolinera');
    }

}
?>