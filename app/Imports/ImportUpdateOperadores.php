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

class ImportUpdateOperadores implements ToCollection, WithHeadingRow, WithValidation {

    use Importable;

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows) {
        
        //echo count($rows);
        //$i = 1;
        foreach ($rows as $row) {
            //$password = substr(md5(microtime()), 1, 8);
            
            //dd($row['nombre_apellido']);
            //echo $i;
            //echo $row['nombre_apellido']."<br>";
            
            //$i++;
            
            $personal = Personal::where('numero_empleado',trim($row['numero_empleado']))->first();
            
            $user_id = $personal->user_id;
            
            $user = User::find($user_id);
            if($row['nombre_apellido'] != ""){
            $user->nombre_apellido = trim($row['nombre_apellido']);
            }
            if($row['email'] != ""){
            $user->email = trim($row['email']);
            }
            if($row['restaurar_contrasena'] != ""){
            if($row['restaurar_contrasena'] == "Si"){
               $password = 'directagroup';
               $user->password = $password;
            }
            }
            if($row['estatus_personal'] != ""){
            if($row['estatus_personal'] == "activo"){
              $user->estatus_id = 1;
            }else{
              $user->estatus_id = 2; 
            }
            }
            $user->ficha_personal = 1;
            $user->save();
            
            if($row['in_telefonico'] != ""){
            $personal->in_telefonico = trim($row['in_telefonico']);
            }
            if($row['usuario_tlmk'] != ""){
            $personal->login_telefonico = trim($row['usuario_tlmk']);
            }
            
            $supervisor = Personal::where('numero_empleado', '=', $row['jefe_inmediato'])->first();
            $personal->jefe_inmediato_id = $supervisor->id;
            $personal->jefe_inmediato_segundo_id = $supervisor->jefe_inmediato_id;
            
            if($row['estatus_personal'] != ""){
            if($row['estatus_personal'] == "activo"){
              $personal->estatus = 1;
            }else{
              $personal->estatus = 2; 
            }
            }
            $personal->campana_id = Campania::where('nombre_campana', '=', $row['campana'])->first()->id;
            $personal->save();
       
            
            //$data = ['nombre_apellido' => $user->nombre_apellido, 'usuario' => $user->usuario, 'password' => $password];
            //$mail = $user->notify(new NewUser($data));

        }
    }

    public function rules(): array {
        return [
            'numero_empleado' => function($attribute, $value, $onFailure) {
                if (trim($value) == "") {
                    $onFailure('El numero de empleado no puede estar vacio.');
                }
                if (!Personal::where('numero_empleado', '=', trim($value))->exists()) {
                    $onFailure('El numero de empleado ingresado no existe.');
                }
            },
            'nombre_apellido' => function($attribute, $value, $onFailure) {
                if (trim($value) == "") {
                    $onFailure('El nombre y apellido no pueden estar vacios.');
                }
            },
            'campana' => function($attribute, $value, $onFailure) {
                if (!Campania::where('nombre_campana', '=', trim($value))->exists()) {
                    $onFailure('El nombre de la campana ingresado no existe.');
                }
            },
            'jefe_inmediato' => function($attribute, $value, $onFailure) {
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
