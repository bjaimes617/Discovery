<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use App\Notifications\ResetPassword as ResetPasswordNotification;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable  implements MustVerifyEmail
{

    use Notifiable;
    use HasRoleAndPermission;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre_apellido',
        'usuario',
        'email',
        'password',
        'estatus_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'fa2_secret'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = \Hash::make($value);
        }
    }

    public function setUsuarioAttribute($value)
    {
        $this->attributes['usuario'] = strtolower($value);
    }

    public function setNombreApellidoAttribute($value)
    {
        $this->attributes['nombre_apellido'] = strtolower($value);
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    //GET METHOD

    public function getUsuarioAttribute($value)
    {
        return strtolower($value);
    }

    public function getNombreApellidoAttribute($value)
    {
        return ucwords(strtolower($value));
    }


    public function getEmailAttribute($value)
    {
        return strtolower($value);
    }

    public function getFichaPersonalAttribute($value)
    {
        switch ($value):
            case 0:
                $ficha = "No";
                break;
            case 1:
                $ficha = "Si";
                break;
        endswitch;
        return $ficha;
    }

    public function getFa2Attribute($value)
    {

        switch ($value):
            case 0:
                $fa = "No";
                break;
            case 1:
                $fa = "Si";
                break;
        endswitch;
        return $fa;
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d/m/Y');
    }

    public function sendPasswordResetNotification($token)
    {

        if ($this->estatus_id != 2) {
            // dd($this->estatus_id,$this,"llego");
            $this->notify(new ResetPasswordNotification($token));
        } else {
            return false;
        }
    }


    public function estatus()
    {
        return $this->belongsTo(Estatus::class, 'estatus_id');
    }

    public function personal()
    {
        return $this->hasOne(Personal::class, 'user_id', 'id');
    }
}
