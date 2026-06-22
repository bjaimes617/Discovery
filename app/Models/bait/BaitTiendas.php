<?php

namespace App\Models\bait;

use Illuminate\Database\Eloquent\Model;

class BaitTiendas extends Model
{
    protected $table = 'bait_tiendas';
    protected $fillable = ['id', 'unidad', 'negocio', 'direccion', 'municipio_id', 'active'];
    public $timestamps = false;

    public function TiendaPerteneceAMunicipio()
    {
        return $this->belongsTo(BaitMunicipios::class, 'municipio_id', 'id');
    }
}
