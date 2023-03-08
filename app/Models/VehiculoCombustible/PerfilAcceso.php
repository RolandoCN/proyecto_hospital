<?php

namespace App\Models\VehiculoCombustible;

use Illuminate\Database\Eloquent\Model;

class PerfilAcceso extends Model
{
    protected $table = 'vc_perfil_acceso';
    protected $primaryKey  = 'id_perfil_acceso';
    public $timestamps = false;

    public function menu(){
        return $this->belongsTo('App\Models\VehiculoCombustible\Menu', 'id_menu', 'id_menu');
    }
}
?>