<?php

namespace App\Models\bait;

use Illuminate\Database\Eloquent\Model;
use App\Models\Personal;
use App\Models\bait\BaitHistoricos;
use App\Models\bait\BaitEstatus;
use App\Models\User;

class BaitEstatusConcentra extends Model
{
    protected $table = 'bait_estatus_concentra';
    public $timestamps = false;
    protected $fillable = ['descripcion', 'active'];
}
