<?php

namespace App\Models\renovaciones;

use Illuminate\Database\Eloquent\Model;

class renovacionesEstatusModel extends Model
{
    protected $table = 'renovaciones_estatus';
    protected $fillable = [    
        'descripcion',
        'active'
    ];
}
