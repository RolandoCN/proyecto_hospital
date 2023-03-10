<?php

namespace App\Models\VehiculoCombustible;

use Illuminate\Database\Eloquent\Model;

class GasolineraCombustible extends Model
{
    protected $table = 'vc_gasolinera_comb';
    protected $primaryKey  = 'idgasolinera_comb';
    public $timestamps = false;

    public function gasolinera(){
        return $this->belongsTo('App\Models\VehiculoCombustible\Gasolinera', 'id_gasolinera', 'id_gasolinera');
    }

    public function combustible(){
        return $this->belongsTo('App\Models\VehiculoCombustible\TipoCombustible', 'id_tipocombustible', 'id_tipocombustible');
    }

}
?>