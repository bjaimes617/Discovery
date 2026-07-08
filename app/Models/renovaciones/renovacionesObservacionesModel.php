<?php

namespace App\Models\renovaciones;

use Illuminate\Database\Eloquent\Model;

class renovacionesObservacionesModel extends Model
{
    protected $table = 'renovaciones_observaciones';
    protected $fillable = [    
        'descripcion',
        'estatus_id',
        'active'
    ];
}
