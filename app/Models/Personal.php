<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Personal extends Model
{

    protected $table = 'personal';

    protected $fillable = [
        'in_telefonico',
        'numero_empleado',
        'login_telefonico',
        'fecha_ingreso',
        'cargo_id',
        'user_id',
        'jefe_inmediato_id',
        'jefe_inmediato_segundo_id',
        'estatus',
        'campana_id',
    ];

    public function setFechaIngresoAttribute($value)
    {
        $arrayDate = explode("/", $value);
        $date = $arrayDate[2] . "-" . $arrayDate[1] . "-" . $arrayDate[0];
        $this->attributes['fecha_ingreso'] = $date;
    }

    public function getFechaIngresoAttribute($value)
    {
        $arrayDate = explode("-", $value);
        $date = $arrayDate[2] . "/" . $arrayDate[1] . "/" . $arrayDate[0];
        return $date;
    }

    public function setFechaBajaAttribute($value)
    {
        if ($value) {
            $arrayDate = explode("/", $value);
            $date = $arrayDate[2] . "-" . $arrayDate[1] . "-" . $arrayDate[0];
            $this->attributes['fecha_baja'] = $date;
        } else
            $this->attributes['fecha_baja'] = null;
    }

    public function getFechaBajaAttribute($value)
    {
        if ($value) {
            $arrayDate = explode("-", $value);
            $date = $arrayDate[2] . "/" . $arrayDate[1] . "/" . $arrayDate[0];
            return $date;
        }
    }

    public function getEstatusAttribute($value)
    {
        switch ($value):
            case 1:
                $estatus = "Activo";
                break;
            case 2:
                $estatus = "Baja";
                break;
        endswitch;
        return $estatus;
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'cargo_id');
    }

    public function campana()
    {
        return $this->belongsTo(Campania::class, 'campana_id');
    }

    public function RelationUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
