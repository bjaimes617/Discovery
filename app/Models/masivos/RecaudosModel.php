<?php

namespace App\Models\masivos;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class RecaudosModel extends Model
{
    protected $table = 'claro_masivo_recaudos';

    public function relationProductos()
    {
        return $this->belongsTo(ProductosModel::class, 'producto_id', 'id');
    }
}
