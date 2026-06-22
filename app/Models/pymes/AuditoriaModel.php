<?php

namespace App\Models\pymes;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class AuditoriaModel extends Model
{
    protected $table = 'claro_pymes_auditoria';

    public function relationAuditUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
