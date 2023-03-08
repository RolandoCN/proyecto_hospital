<?php

namespace App\Models\VehiculoCombustible;

use Illuminate\Database\Eloquent\Model;

class GestionMenu extends Model
{
    protected $table = 'vc_gestion_menu';
    protected $primaryKey  = 'id_gestion_menu';
    public $timestamps = false;

    public function gestion(){
        return $this->belongsTo('App\Models\VehiculoCombustible\Gestion', 'id_gestion', 'id_gestion');
    }


    public function menu(){
        return $this->belongsTo('App\Models\VehiculoCombustible\Menu', 'id_menu', 'id_menu');
    }

}
?>