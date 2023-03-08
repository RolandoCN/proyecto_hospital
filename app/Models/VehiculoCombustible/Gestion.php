<?php

namespace App\Models\VehiculoCombustible;

use Illuminate\Database\Eloquent\Model;

class Gestion extends Model
{
    protected $table = 'vc_gestion';
    protected $primaryKey  = 'id_gestion';
    public $timestamps = false;

}
?>