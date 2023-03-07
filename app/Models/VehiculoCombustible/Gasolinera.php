<?php

namespace App\Models\VehiculoCombustible;

use Illuminate\Database\Eloquent\Model;

class Gasolinera extends Model
{
    protected $table = 'vc_gasolinera';
    protected $primaryKey  = 'id_gasolinera ';
    public $timestamps = false;

}
?>