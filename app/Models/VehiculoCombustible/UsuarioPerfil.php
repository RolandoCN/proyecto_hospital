<?php

namespace App\Models\VehiculoCombustible;

use Illuminate\Database\Eloquent\Model;

class UsuarioPerfil extends Model
{
    protected $table = 'vc_perfil_usuario';
    protected $primaryKey  = 'idperfil_usuario';
    public $timestamps = false;

    public function nombre_perfil(){
        return $this->belongsTo('App\Models\VehiculoCombustible\Perfil', 'id_perfil', 'id_perfil')->where('estado', 'A');
    }
}
?>