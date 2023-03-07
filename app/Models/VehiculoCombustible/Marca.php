<?php

namespace App\Models\VehiculoCombustible;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $table = 'vc_marca';
    protected $primaryKey  = 'idmarca';
    public $timestamps = false;

}
?>