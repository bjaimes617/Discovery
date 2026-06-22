<?php

namespace App\Models\bait;

use Illuminate\Database\Eloquent\Model;

class BaitMunicipios extends Model
{
    protected $table = 'bait_municipios';
    protected $fillable = ['id', 'municipio', 'estado_id', 'active'];
    public $timestamps = false;

    public function RelationsTiendas()
    {
        return $this->hasMany(BaitTiendas::class, 'municipio_id', 'id');
    }

    public function MunicipioPerteneceAEstado()
    {
        return $this->belongsTo(BaitEstados::class, 'estado_id', 'id');
    }
}
