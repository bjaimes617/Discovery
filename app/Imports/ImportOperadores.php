<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\User;
use App\Models\Personal;
use App\Models\Campania;
use App\Models\Cargo;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use App\Notifications\NewUser;
use Illuminate\Support\Facades\Session;

class ImportOperadores implements ToCollection, WithHeadingRow, WithValidation
{

    use Importable;

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        try {
            foreach ($rows as $row) {


                $password = 'directagroup';
                $user = new User();
                $user->nombre_apellido = trim($row['nombre_apellido']);
                $user->usuario = trim($row['usuario']);
                $user->email = trim($row['email']);
                $user->password = $password;
                $user->estatus_id = 3;
                $user->ficha_personal = 1;
                $user->email_verified_at = date("Y-m-d H:i:s");
                $user->save();

                switch ($row['campana']) {
                    case 'Claro Masivo':
                        switch ($row['cargo']) {
                            case 'Operador':
                                $rol = 61;
                                $cargo = 7; //operador
                                break;
                            case 'Supervisor':
                                $rol = 62;
                                $cargo = 4; ///supervisor
                                break;
                            case 'Backoffice':
                                $rol = 63;
                                $cargo = 6; //backoffice
                                break;
                            case 'Coordinador':
                                $rol = 64;
                                $cargo = 3; //coordinador
                                break;
                        }
                        break;
                    case 'Bait':
                        switch ($row['cargo']) {
                            case 'Operador':
                                $rol = 69;
                                $cargo = 7; //operador
                                break;
                            case 'Supervisor':
                                $rol = 70;
                                $cargo = 4; ///supervisor
                                break;
                            case 'Backoffice':
                                $rol = 71;
                                $cargo = 6; //backoffice
                                break;
                            case 'Coordinador':
                                $rol = 72;
                                $cargo = 3; //coordinador
                                break;
                        }
                        break;
                    case 'Renovaciones':
                        switch ($row['cargo']) {
                            case 'Operador':
                                $rol = 74;
                                $cargo = 7; //operador
                                break;
                            case 'Supervisor':
                                $rol = 75;
                                $cargo = 4; ///supervisor
                                break;
                            case 'Backoffice':
                                $rol = 77;
                                $cargo = 6; //backoffice
                                break;
                            case 'Coordinador':
                                $rol = 76;
                                $cargo = 3; //coordinador
                                break;
                        }
                        break;
                }

                $user->attachRole($rol);

                $personal = new Personal();
                $personal->in_telefonico = trim($row['user_response']);
                $personal->numero_empleado = trim($row['numero_empleado']);
                $personal->login_telefonico = NULL;
                $personal->fecha_ingreso = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['fecha_ingreso'])->format('d/m/Y');
                $personal->user_id = $user->id;
                $supervisor = Personal::where('numero_empleado', '=', $row['jefe_inmediato'])->first();
                $personal->jefe_inmediato_id = $supervisor->id;
                $personal->jefe_inmediato_segundo_id = $supervisor->jefe_inmediato_id;
                $personal->estatus = 1;
                $personal->campana_id = Campania::where('nombre_campana', '=', $row['campana'])->first()->id;
                $personal->cargo_id = $cargo;
                $personal->save();

                //$data = ['nombre_apellido' => $user->nombre_apellido, 'usuario' => $user->usuario, 'password' => $password];
                //$mail = $user->notify(new NewUser($data));

            }
            Session::flash('success', 'El archivo fue cargado exitosamente por favor valide los registros.');
            return true;
        } catch (\Exception $e) {
            Session::flash('error', 'Ocurrio un error al registrar. ' . $e->getMessage());
            return false;
        }
    }

    public function rules(): array
    {
        return [
            'nombre_apellido' => function ($attribute, $value, $onFailure) {
                if (trim($value) == "") {
                    $onFailure('El nombre y apellido no pueden estar vacios.');
                }
            },
            'usuario' => function ($attribute, $value, $onFailure) {
                if (User::where('usuario', '=', trim($value))->exists()) {
                    $onFailure('El usuario ingresado, ya se encuentra registrado.');
                }
            },
            'email' => function ($attribute, $value, $onFailure) {
                if (User::where('email', '=', trim($value))->exists()) {
                    $onFailure('El email ingresado, ya se encuentra registrado.');
                }
            },
            'numero_empleado' => function ($attribute, $value, $onFailure) {
                if (Personal::where('numero_empleado', '=', trim($value))->exists()) {
                    $onFailure('El numero de empleado, ya se encuentra registrado.');
                }
            },
            'campana' => function ($attribute, $value, $onFailure) {
                if (!Campania::where('nombre_campana', '=', trim($value))->exists()) {
                    $onFailure('El nombre de la campana ingresado no existe.');
                }
            },
            'cargo' => function ($attribute, $value, $onFailure) {
                if (!Cargo::where('nombre_cargo', '=', trim($value))->exists()) {
                    $onFailure('El nombre del cargo ingresado no existe.');
                }
            },
            'jefe_inmediato' => function ($attribute, $value, $onFailure) {
                if (!Personal::where('numero_empleado', '=', trim($value))->exists()) {
                    $onFailure('El numero de empleado del jefe inmediato ingresado no existe.');
                } else {
                    if (Personal::where('numero_empleado', '=', trim($value))->first()->cargo->nombre_cargo != 'Supervisor') {
                        $onFailure('El numero de empleado del jefe inmediato ingresado no corresponde al de un supervisor.');
                    }
                }
            }
        ];
    }
}
