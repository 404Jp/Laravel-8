<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    protected $fillable = ['titulo', 'contenido','lider'];
    public function liderGrupo()
    {
        return $this->belongsTo(LiderGrupo::class, 'id_grupo', 'id');
    }
}