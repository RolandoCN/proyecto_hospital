<?php

namespace App\Models\VehiculoCombustible;

use Illuminate\Database\Eloquent\Model;

class OrdenesCombustible extends Model
{
    protected $table = 'vc_ordenes_combustible';
    protected $primaryKey  = 'id_ordenes_combustible ';
    public $timestamps = false;

}
?>