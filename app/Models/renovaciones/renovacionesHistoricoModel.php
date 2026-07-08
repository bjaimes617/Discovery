<?php

namespace App\Models\renovaciones;

use Illuminate\Database\Eloquent\Model;

class renovacionesHistoricoModel extends Model
{
    protected $table = 'renovaciones_historico';   

    public function relationEstatus()
    {
        return $this->belongsTo(renovacionesEstatusModel::class, 'estatus_id', 'id');
    }
    public function relationObservaciones()
    {
        return $this->belongsTo(renovacionesObservacionesModel::class, 'observaciones_id', 'id');
    }
}
