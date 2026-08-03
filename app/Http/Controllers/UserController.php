<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Cargo;
use App\Models\Campania;
use App\Models\Personal;
use App\Models\SupervisorAsignado;
use Illuminate\Support\Facades\Session;
use jeremykenedy\LaravelRoles\Models\Role;
use jeremykenedy\LaravelRoles\Models\Permission;
use App\Notifications\NewUser;
use App\Models\Estatus;
use App\Models\PasswordUpdate;
use Carbon\Carbon;
use App\Imports\ImportOperadores;
use App\Imports\ImportUpdateOperadores;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsuarioExport;
use Illuminate\Support\Facades\DB;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;
use Illuminate\Support\Facades\Hash;

//use Illuminate\Support\Facades\Config;
//
//use App\Notifications\PasswordUpdateClient;

class UserController extends Controller
{

    use ActivityLogger;

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('check.password')->except(['changePassword', 'checkCurrentPassword', 'checkNewPassword', 'updatePassword']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return response()->view('users/index');
    }

    /**
     * Get all clients.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getUsers(Request $request)
    {

        if ($request->ajax()) {
            $data = array();
            if (Auth::user()->hasRole(['admin'])) {
                $users = User::orderBy('created_at', 'desc');
            } else {
                $users = User::whereNotIn('id', [1, 2])->orderBy('created_at', 'desc');
            }

            $users->chunk(150, function ($chunks) use (&$data) {
                foreach ($chunks as $rs) {
                    $user = array();
                    $user['id'] = $rs->id;
                    $user['Nombre y Apellido'] = $rs->nombre_apellido;
                    $user['Usuario'] = $rs->usuario;
                    $user['Email'] = $rs->email;
                    $user['Ficha de Personal'] = $rs->ficha_personal;

                    if (isset($rs->personal->campana))
                        $user['campania'] = $rs->personal->campana->nombre_campana;
                    else
                        $user['campania'] = 'NA';

                    if ($rs->roles->first() !== null)
                        $user['Rol'] = $rs->roles->first()->name;
                    else
                        $user['Rol'] = 'NA';

                    $user['Autenticacion'] = $rs->fa2;
                    $user['Creado El'] = $rs->created_at;

                    $user['Estatus'] = $rs->estatus->nombre_estatus;

                    $actionsHtml = "";
                    if (Auth::user()->hasPermission('users.edit'))
                        $actionsHtml = "<a class='btn btn-sm btn-clean btn-icon btn-icon-md' href='" . route('user.edit', $rs->id) . "' title='Editar'><i class='la la-edit'></i></a> ";

                    if (Auth::user()->hasPermission('users.delete'))
                        $actionsHtml .= "<a class='btn btn-sm btn-clean btn-icon btn-icon-md deleterow' href='javascript:void(0)' data-toggle='modal' title='Eliminar' data-url='user/" . $rs->id . "' data-title='" . $rs->nombre_apellido . "'><i class='la la-trash'></i></a>";

                    $user["Acciones"] = $actionsHtml;

                    $data[] = $user;
                }
            });

            $results = array(
                "sEcho" => 1,
                "iTotalRecords" => count($data),
                "iTotalDisplayRecords" => count($data),
                "aaData" => $data
            );

            echo json_encode($results);
        }
    }


    /**
     * Display new client view.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->hasRole(['admin'])) {
            $roles = Role::orderBy('slug', 'asc')->pluck('name', 'id');
        } else {
            $roles = Role::where('slug', '<>', 'admin')->orderBy('slug', 'asc')->pluck('name', 'id');
        }

        $cargos = Cargo::where('estatus_id', '=', 1)->orderBy('nombre_cargo', 'asc')->pluck('nombre_cargo', 'id');
        $campanias = Campania::where('estatus_id', '=', 1)->orderBy('nombre_campana', 'asc')->pluck('nombre_campana', 'id');
        $jefes = Personal::leftJoin('cargos', 'personal.cargo_id', '=', 'cargos.id')
            ->leftJoin('users', 'personal.user_id', '=', 'users.id')
            ->where('personal.estatus', '=', 1)
            ->where('cargos.es_jefe', '=', 1)
            ->orderBy('users.nombre_apellido', 'asc')->pluck('users.nombre_apellido', 'personal.id');
        $supervisores = Personal::leftJoin('cargos', 'personal.cargo_id', '=', 'cargos.id')
            ->leftJoin('users', 'personal.user_id', '=', 'users.id')
            ->where('personal.estatus', '=', 1)
            ->where('cargos.nombre_cargo', '=', 'Supervisor')
            ->orderBy('users.nombre_apellido', 'asc')->pluck('users.nombre_apellido', 'personal.id');
        return response()->view('users/create', ['roles' => $roles, 'cargos' => $cargos, 'campanias' => $campanias, 'jefes' => $jefes, 'supervisores' => $supervisores]);
    }

    /**
     * Check Username.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = request()->validate([
            'nombre_apellido' => 'required',
            'usuario' => 'required|unique:users',
            'email' => 'email|required|unique:users'
        ]);

        //$password = substr(md5(microtime()), 1, 8);
        $password = 'directagroup';

        $user = new User();
        $user->nombre_apellido = $request->nombre_apellido;
        $user->usuario = $request->usuario;
        $user->email = $request->email;
        $user->password = $password;
        $user->estatus_id = 3;
        $user->email_verified_at = date("Y-m-d H:i:s");
        if ($request->auth2fa != null)
            $user->fa2 = 1;
        if ($request->valida_ficha_personal != null)
            $user->ficha_personal = 1;
        $user->save();
        $user->attachRole($request->roles);


        if ($request->valida_ficha_personal != null) {
            $data = request()->validate([
                'user_response' => 'required',
                'numero_empleado' => 'required|unique:personal',
                'fecha_ingreso' => 'required',
                'cargo' => 'required'
            ]);

            $personal = new Personal();
            $personal->user_id = $user->id;
            $personal->in_telefonico    = $request->user_response;
            $personal->numero_empleado  = $request->numero_empleado;
            $personal->login_telefonico = null;
            $personal->estatus = 1;
            $personal->fecha_ingreso = $request->fecha_ingreso;
            $personal->cargo_id = $request->cargo;
            $personal->campana_id = $request->campania;
            $personal->jefe_inmediato_id = $request->jefe_inmediato;
            $personal->jefe_inmediato_segundo_id = $request->segundo_jefe_inmediato;
            $personal->save();

            if ($request->cargo == 5) {
                $supervisores = $request->supervisores;
                foreach ($supervisores as $item => $key) {
                    $superAsignados = new SupervisorAsignado();
                    $superAsignados->validador_id = $personal->id;
                    $superAsignados->supervisor_id = $key['supervisor'];
                    $superAsignados->save();
                }
            }
        }


        /* try {
          $data = ['nombre_apellido' => $user->nombre_apellido, 'usuario' => $user->usuario, 'password' => $password];
          $mail = $user->notify(new NewUser($data));
          Session::flash('success', 'Fue enviando el email al usuario.');
          } catch (\Exception $e) { // Using a generic exception
          Session::flash('warning', 'No se pudo enviar el email al usuario.');
          } */
        Session::flash('success', 'El usuario fue registrado correctamente.');
        return response()->json(["url" => route('user.list')]);
    }

    /**
     * Display new client view.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $permissions = Permission::all();

        $rol = $user->roles()->first();

        $estatus = Estatus::where('grupo', '=', 'a')->pluck('nombre_estatus', 'id');
        $personal = Personal::where('user_id', '=', $id)->first();

        if (Auth::user()->hasRole(['admin'])) {
            $roles = Role::orderBy('slug', 'asc')->get();
        } else {
            $roles = Role::where('slug', '<>', 'admin')->orderBy('slug', 'asc')->get();
        }

        $permissionrols = Role::find($rol->id)->permissions()->select('permission_id')->pluck('permission_id')->toarray();
        ///permisos adicionales del usuario
        $permisosAdicionales = $user->getpermissions()->whereNotIn('id', $permissionrols)->pluck('id')->toarray();

        $cargos = Cargo::where('estatus_id', '=', 1)->orderBy('nombre_cargo', 'asc')->pluck('nombre_cargo', 'id');
        $campanias = Campania::where('estatus_id', '=', 1)->orderBy('nombre_campana', 'asc')->pluck('nombre_campana', 'id');

        $jefes = Personal::select('users.nombre_apellido', 'personal.id')
            ->leftJoin('cargos', 'personal.cargo_id', '=', 'cargos.id')
            ->leftJoin('users', 'personal.user_id', '=', 'users.id')
            ->where('personal.estatus', '=', 1)
            ->where('cargos.es_jefe', '=', 1)
            ->orderBy('users.nombre_apellido', 'asc')->get();

        $supervisores = Personal::select('users.nombre_apellido', 'personal.id')
            ->leftJoin('cargos', 'personal.cargo_id', '=', 'cargos.id')
            ->leftJoin('users', 'personal.user_id', '=', 'users.id')
            ->where('personal.estatus', '=', 1)
            ->where('cargos.nombre_cargo', '=', 'Supervisor')
            ->orderBy('users.nombre_apellido', 'asc')->get();

        if (isset($personal->cargo_id) && $personal->cargo_id == 5) {
            $superAsignados = SupervisorAsignado::where('validador_id', '=', $personal->id)->orderBy('supervisor_id', 'asc')->pluck('supervisor_id');
            return response()->view('users/edit', [
                'estatus' => $estatus,
                'roleuser' => $rol,
                'roles' => $roles,
                "permisosadicionales"   => $permisosAdicionales,
                "permisosrol" => $permissionrols,
                'permission' => $permissions,
                'cargos' => $cargos,
                'campanias' => $campanias,
                'jefes' => $jefes,
                'supervisores' => $supervisores,
                'user' => $user,
                'personal' => $personal,
                'superAsignados' => $superAsignados
            ]);
        } else
            return response()->view('users/edit', [
                'estatus' => $estatus,
                'roleuser' => $rol,
                'roles' => $roles,
                "permisosadicionales"   => $permisosAdicionales,
                "permisosrol" => $permissionrols,
                'permission' => $permissions,
                'cargos' => $cargos,
                'campanias' => $campanias,
                'jefes' => $jefes,
                'supervisores' => $supervisores,
                'user' => $user,
                'personal' => $personal
            ]);
    }

    /**
     * Check Username.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = request()->validate([
            'nombre_apellido' => 'required',
            'usuario' => 'required|unique:users,id,' . $id,
            'email' => 'unique:users,email,' . $id
        ]);

        $user = User::find($id);
        $user->nombre_apellido = $request->nombre_apellido;
        $user->usuario = $request->usuario;
        $user->email = $request->email;

        if ($request->estatus)
            $user->estatus_id = $request->estatus;


        if ($request->auth2fa != null)
            $user->fa2 = 1;
        else {
            $user->fa2 = 0;
            $user->fa2_secret = null;
        }

        if ($request->reset2fa != null)
            $user->fa2_secret = null;


        if ($request->valida_ficha_personal != null)
            $user->ficha_personal = 1;
        else
            $user->ficha_personal = 0;

        if ($request->resetpass != null) {
            $user->password_updated_at = null;
            $user->password = "directagroup";
        }

        $user->save();

        if ($request->filled('roles')) {
            $user->roles()->sync($request->roles);
        } else {
            $user->detachAllRoles();
        }
        // dd($request->all());
        if (isset($request->permisos) && count($request->permisos) > 0) {
            $user->syncPermissions($request->permisos);
        } else {
            $user->detachAllPermissions();
        }


        if ($request->valida_ficha_personal != null) {
            $data = request()->validate([
                'user_response' => 'required',
                'numero_empleado' => 'required|unique:personal,user_id,' . $id,
                'fecha_ingreso' => 'required',
                'cargo' => 'required'
            ]);

            if (!Personal::where('user_id', '=', $id)->exists())
                $personal = new Personal();
            else
                $personal = Personal::where('user_id', '=', $id)->first();
            $personal->user_id          = $user->id;
            $personal->in_telefonico    = $request->user_response;
            $personal->numero_empleado  = $request->numero_empleado;
            $personal->login_telefonico = null;
            $personal->fecha_ingreso    = $request->fecha_ingreso;
            $personal->cargo_id         = $request->cargo;
            $personal->campana_id       = $request->campania;
            $personal->jefe_inmediato_id = $request->jefe_inmediato;
            $personal->jefe_inmediato_segundo_id = $request->segundo_jefe_inmediato;
            $personal->estatus = $request->estatus_personal;

            if ($request->estatus_personal == 2)
                $personal->fecha_baja = $request->fecha_baja;
            else
                $personal->fecha_baja = null;
            $personal->save();
            /*
            SupervisorAsignado::where('validador_id', '=', $personal->id)->delete();
            if ($request->cargo == 5) {
                $supervisores = $request->supervisores;
                foreach ($supervisores as $item => $key) {
                    $superAsignados = new SupervisorAsignado();
                    $superAsignados->validador_id = $personal->id;
                    $superAsignados->supervisor_id = $key['supervisor'];
                    $superAsignados->save();
                }
            }*/
        }
        return response()->json(["url" => route('user.list')]);
    }

    /**
     * Remove the specified client from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $idpersonal = null;
            $id = $user->id;

            if (Personal::select('jefe_inmediato_id', 'jefe_inmediato_segundo_id', 'id')->where('user_id', $id)->exists()) {
                $personal = Personal::select('jefe_inmediato_id', 'jefe_inmediato_segundo_id', 'id')->where('user_id', $id)->first();
                $idpersonal = $personal->id;
                $personal->delete();
            }

            $user->delete();

            ActivityLogger::activity("Usuario eliminado " . $user->nombre_apellido . " id del personal " . $idpersonal);
            return response()->json(["message" => 'El usuario fue eliminado exitosamente.', "type" => 'success']);

            /* if ($personal == null || ($personal->jefe_inmediato_id == NULL && $personal->jefe_inmediato_segundo_id == NULL)) {
                if ($user->delete()) {
                    if ($personal != null) {
                        Personal::where('user_id', '=', $id)->delete();
                    }
                    ActivityLogger::activity("Usuario eliminado " . $user->nombre_apellido . " id del personal " . $personal->id);
                    return response()->json(["message" => 'El usuario fue eliminado exitosamente.', "type" => 'success']);
                } else {
                    return 'false';
                }
            } else {
                return response()->json(["message" => 'El usuario tiene personal asignado, por favor verifique antes de eliminar', "type" => 'danger']);
            }*/
        } catch (\Exception $e) {
            return response()->json(["message" => 'Ocurrio un problema ' . $e->getMessage(), "type" => 'danger']);
        }
    }

    /**
     * Check Username.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkUsername(Request $request)
    {
        if ($request->ajax()) {
            $user = User::where('usuario', '=', $request->usuario);
            if ($request->id)
                $user->where('id', '<>', $request->id);
            if ($user->count() > 0) {
                return 'false';
            } else {
                return 'true';
            }
        }
    }

    /**
     * Check Email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkEmail(Request $request)
    {
        if ($request->ajax()) {
            $user = User::where('email', '=', $request->email);
            if ($request->id)
                $user->where('id', '<>', $request->id);
            if ($user->count() > 0) {
                return 'false';
            } else {
                return 'true';
            }
        }
    }

    public function AddPermissionsAditionals(Request $request)
    {

        $permissions = Role::find($request->id)->permissions()->select('permission_id')->get();
        foreach ($permissions as $id) {
            $array[] = $id->permission_id;
        }
        return json_encode($array);
    }

    public function checkNumeroEmpleado(Request $request)
    {
        if ($request->ajax()) {
            $personal = Personal::where('numero_empleado', '=', $request->numero_empleado);
            if ($request->id)
                $personal->where('user_id', '<>', $request->id);
            if ($personal->count() > 0) {
                return 'false';
            } else {
                return 'true';
            }
        }
    }

    public function profile()
    {
        return response()->view('users/profile');
    }

    public function changePassword()
    {
        if (Session::has('password'))
            return response()->view('users/changepassword');
        else
            return redirect()->route('dashboard');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            //'currentpassword' => "required",
            'newpassword' => 'required',
        ]);

        $user = Auth::user();

        if ($request->currentpassword && !\Hash::check($request->currentpassword, Auth::user()->password)) {
            Session::flash('error', 'La contraseña actual no coincide con la que ingreso, intente de nuevo');
            return redirect()->back();
        }
        $lastPassword = PasswordUpdate::where('user_id', '=', Auth::user()->id)->orderBy('id', 'desc')->take(config('app.lastpassword'))->get();
        if (count($lastPassword) > 0 && Auth::user()->estatus_id != 3) {
            foreach ($lastPassword as $pass) {
                if (\Hash::check($request->newpassword, $pass->password)) {
                    Session::flash('error', 'La contraseña nueva no puede ser igual a las ultimas tres (03) que utilizaste, intente de nuevo');
                    return redirect()->back();
                }
            }
        } else {
            $user->estatus_id = 1;
        }
        $user->password = $request->newpassword;
        $user->password_updated_at = Carbon::now();
        $pwu = new PasswordUpdate();
        $pwu->password = $user->password;
        $pwu->user_id = Auth::user()->id;
        $pwu->save();

        if ($user->save()) {
            Session::forget('password');
            Session::flash('success', 'Su contraseña fue actualizada exitosamente.');
            if ($request->currentpassword)
                return redirect()->route('user.profile');
            else
                return redirect()->route('dashboard');
        }
    }

    /**
     * Check Current Password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkCurrentPassword(Request $request)
    {
        if ($request->ajax()) {
            if (\Hash::check($request->currentpassword, Auth::user()->password)) {
                return 'true';
            } else {
                return 'false';
            }
        }
    }

    /**
     * Check New Password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkNewPassword(Request $request)
    {
        if ($request->ajax()) {
            $lastPassword = PasswordUpdate::where('user_id', '=', Auth::user()->id)->orderBy('id', 'desc')->take(config('app.lastpassword'))->get();
            if (count($lastPassword) > 0 && Auth::user()->estatus_id != 3) {
                foreach ($lastPassword as $pass) {
                    if (\Hash::check($request->newpassword, $pass->password)) {
                        return 'false';
                    }
                }
                return 'true';
            } else {
                return 'true';
            }
        }
    }

    /**
     * Display new client view.
     *
     * @return \Illuminate\Http\Response
     */
    public function cargaMasiva()
    {
        return response()->view('users/masiva');
    }

    public function actualizacionMasiva()
    {
        return view('users.massive.update');
    }

    /**
     * Check Username.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $data = request()->validate([
            'archivo' => 'required'
        ]);

        Excel::import(new ImportOperadores, request()->file('archivo'));
        return redirect()->route('user.massive');
    }

    public function importUpdate(Request $request)
    {
        $data = request()->validate([
            'archivo' => 'required'
        ]);

        Excel::import(new ImportUpdateOperadores, request()->file('archivo'));
        Session::flash('successImport', 'El archivo fue cargado exitosamente por favor valide los registros.');
        return redirect()->route('user.update.massive');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function reporte()
    {
        $campana = Campania::where('estatus_id', '=', 1)->orderBy('nombre_campana', 'asc')->pluck('nombre_campana', 'id');
        return view('users.reporte', ['campana' => $campana]);
    }

    public function export(Request $request)
    {

        $sql = Personal::select('personal.*', DB::raw("uper.nombre_apellido as nombre_apellido"), DB::raw("sup.nombre_apellido AS supervisor"), DB::raw("sup2.nombre_apellido AS supervisor2"), 'cargos.nombre_cargo', 'personal.fecha_ingreso', 'uper.*', 'campanias.nombre_campana', 'estatus.nombre_estatus', 'roles.name')
            ->leftJoin('users  as uper', 'personal.user_id', '=', 'uper.id')
            ->leftJoin('personal  as s1per', 'personal.jefe_inmediato_id', '=', 's1per.id')
            ->leftJoin('users  as sup', 's1per.user_id', '=', 'sup.id')
            ->leftJoin('personal  as s2per', 'personal.jefe_inmediato_id', '=', 's2per.id')
            ->leftJoin('users  as sup2', 's2per.user_id', '=', 'sup2.id')
            ->leftJoin('cargos', 'personal.cargo_id', '=', 'cargos.id')
            ->leftJoin('campanias', 'personal.campana_id', '=', 'campanias.id')
            ->leftJoin('estatus', 'uper.estatus_id', '=', 'estatus.id')
            ->leftJoin('role_user', 'uper.id', '=', 'role_user.user_id')
            ->leftJoin('roles', 'roles.id', '=', 'role_user.role_id')
            ->orderBy('personal.in_telefonico', 'asc');



        if ($request->estatus != "") {
            $sql->where("personal.estatus", "=", $request->estatus);
        }

        if ($request->campana != "") {
            $sql->where("personal.campana_id", "=", $request->campana);
        }


        $data = $sql->get();

        $data = $data->toArray();

        return Excel::download(new UsuarioExport($data), 'Reporte de Personal.xlsx');
    }
}
