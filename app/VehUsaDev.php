<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehUsaDev extends Model
{
    protected $table = "siga_veh_usados_dev";

    protected $primaryKey = "id_veh_siga";

    protected $guarded = [];
}
