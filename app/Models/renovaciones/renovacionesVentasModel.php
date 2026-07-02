<?php

namespace App\Models\renovaciones;

use Illuminate\Database\Eloquent\Model;
use App\Models\Personal;
use App\Models\User;
class renovacionesVentasModel extends Model
{
    protected $table = 'renovaciones_ventas';

    public function relationSupervisor()
    {
        return $this->belongsTo(Personal::class, 'supervisor_id', 'id');
    }

    public function relationCoordinador()
    {
        return $this->belongsTo(Personal::class, 'coordinador_id', 'id');
    }

    public function relationPersonal()
    {
        return $this->belongsTo(Personal::class, 'personal_id', 'id');
    }

    public function relationEstatus()
    {
        return $this->belongsTo(renovacionesEstatusModel::class, 'estatus_id', 'id');
    }

    public function relationHistorico()
    {
        return $this->hasMany(renovacionesHistoricoModel::class, 'renovaciones_ventas_id', 'id')->orderBy('id', 'desc');
    }

     public function RelationUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
