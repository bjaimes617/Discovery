<?php

namespace App\Models\bait;

use Illuminate\Database\Eloquent\Model;
use App\Models\Personal;
use App\Models\bait\BaitHistoricos;
use App\Models\bait\BaitEstatus;
use App\Models\User;

class BaitEstatusIntelix extends Model
{
    protected $table = 'bait_estatus_intelix';
    public $timestamps = false;
    protected $fillable = ['descripcion', 'grupo', 'active'];
}
