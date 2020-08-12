<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HoraSincronizacion extends Model
{
    protected $table = "hora_sincronizacion";

    protected $guarded = [];

    public $timestamps = false;
}
