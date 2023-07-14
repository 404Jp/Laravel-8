<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiderGrupo extends Model
{
    protected $table = 'lider_grupo';

    protected $fillable = [
        'id_lider',
        'nombre_lider',
        'id_grupo',
    ];
}