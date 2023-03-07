<?php

namespace App\Models\VehiculoCombustible;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'vc_departamento';
    protected $primaryKey  = 'iddepartamento ';
    public $timestamps = false;

}
?>