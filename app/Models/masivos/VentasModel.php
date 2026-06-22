<?php

namespace App\Models\masivos;

use Illuminate\Database\Eloquent\Model;
use App\Models\masivos\PlanesModel;
use App\Models\Personal;
use App\Models\User;
use App\Models\masivos\EstatusModel;
use App\Models\masivos\ProductosModel;

class VentasModel extends Model
{
    protected $table = 'claro_masivo_ventas';

    public function relationProducto()
    {
        return $this->belongsTo(ProductosModel::class, 'producto_id', 'id');
    }

    public function relationPlanes()
    {
        return $this->belongsTo(PlanesModel::class, 'plan_id', 'id');
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
        return $this->hasMany(MavisoHistoricosModel::class, 'claro_masivo_ventas_id', 'id')->orderBy('id', 'desc');
    }
}
