<?php

namespace App\Models\bait;

use Illuminate\Database\Eloquent\Model;
use App\Models\Personal;
use App\Models\bait\BaitHistoricos;
use App\Models\bait\BaitEstatus;
use App\Models\User;

class BaitVentas extends Model
{
    protected $table = 'bait_ventas';
    public $timestamps = true;

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
        return $this->belongsTo(BaitEstatus::class, 'estatus_id', 'id');
    }

    public function relationHistorico()
    {
        return $this->hasMany(BaitHistoricos::class, 'bait_ventas_id', 'id')->orderBy('id', 'desc');
    }
    public function relationTienda()
    {
        return $this->belongsTo(BaitTiendas::class, 'tienda_id', 'id');
    }
    public function RelationUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
