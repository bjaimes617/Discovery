<?php

namespace App\Models\masivos;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class AuditoriaModel extends Model
{
    protected $table = 'claro_masivo_auditoria';

    public function relationAuditUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
