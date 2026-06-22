<?php

namespace App\Models\bait;

use Illuminate\Database\Eloquent\Model;

class BaitEstados extends Model
{
    protected $table = 'bait_estados';
    protected $fillable = ['id', 'estado', 'active'];
    public $timestamps = false;
    public function RelationsMunicipios()
    {
        return $this->hasMany(BaitMunicipios::class, 'estado_id', 'id');
    }
}
