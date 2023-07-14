<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class solicitud extends Model
{
    use HasFactory;
    protected $table = 'solicitud';
    protected $fillable = ['id', 'id_grupo','id_user'];
}
