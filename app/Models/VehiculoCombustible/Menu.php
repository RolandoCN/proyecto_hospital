<?php

namespace App\Models\VehiculoCombustible;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'vc_menu';
    protected $primaryKey  = 'id_menu';
    public $timestamps = false;

}
?>