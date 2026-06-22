<?php

namespace App\Models\pymes;

use Illuminate\Database\Eloquent\Model;
use App\Models\pymes\ProductosModel;

class RecaudosModel extends Model
{
    protected $table = 'claro_pymes_recaudos';

    public function relationProductos()
    {
        return $this->belongsTo(ProductosModel::class, 'producto_id', 'id');
    }
}
