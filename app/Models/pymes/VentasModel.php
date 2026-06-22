<?php

namespace App\Models\pymes;

use Illuminate\Database\Eloquent\Model;
use App\Models\pymes\PlanesModel;
use App\Models\Personal;
use App\Models\User;
use App\Models\pymes\EstatusModel;
use App\Models\pymes\PymesHistoricosModel;

class VentasModel extends Model
{
    protected $table = 'claro_pymes_ventas';

    public function relationPlanes()
    {
        return $this->belongsTo(PlanesModel::class, 'plan_id', 'id');
    }

    public function relationProducto()
    {
        return $this->belongsTo(ProductosModel::class, 'producto_id', 'id');
    }

    public function relationUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

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
        return $this->belongsTo(EstatusModel::class, 'estatus_id', 'id');
    }

    public function relationHistorico()
    {
        return $this->hasMany(PymesHistoricosModel::class, 'claro_pymes_ventas_id', 'id');
    }
}
