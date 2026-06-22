<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use jeremykenedy\LaravelRoles\Models\Role;
use jeremykenedy\LaravelRoles\Models\Permission;

class RoleController extends Controller {

    public function __construct() {
        $this->middleware(['auth','verified','check.password']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $permissions = Permission::orderBy('slug', 'asc')->get();
        return response()->view('roles/index', ['permissions' => $permissions]);
    }

    /**
     * Get all roles.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getRoles(Request $request) {
        if ($request->ajax()) {
            $data = array();
            $roles = Role::orderBy('created_at', 'desc')->get();

            foreach ($roles as $rs) {
                $role = array();
                $role['Nombre'] = $rs->name;
                $role['Accion'] = $rs->slug;
                $role['Descripci&oacute;n'] = $rs->description;
                $role['Nivel'] = $rs->level;
                $role['Creado En'] = date('d/m/Y H:i:s', strtotime($rs->created_at));
                
                $roleOne = Role::find($rs->id);
                $rolepermissions = array();
                foreach ($roleOne->permissions()->get() as $rp) {
                    array_push($rolepermissions, $rp->id);
                }

                $actionsHtml = "
                                <a class='btn btn-sm btn-clean btn-icon btn-icon-md editrole' href='javascript:void(0)' data-id='" . $rs->id . "' data-name='" . $rs->name . "' data-slug='" . $rs->slug . "' data-description='" . $rs->description . "' data-level='" . $rs->level . "' data-permissions='" . json_encode($rolepermissions) . "' title='Editar'><i class='la la-edit'></i></a>
                                <a class='btn btn-sm btn-clean btn-icon btn-icon-md deleterow' href='javascript:void(0)' data-toggle='modal' title='Eliminar' data-url='roles/" . $rs->id . "' data-title='" . $rs->name . "'><i class='la la-trash'></i></a>
";
                $role['Acciones'] = $actionsHtml;


                $data[] = $role;
            }
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'slug' => 'required|unique:roles',
            'description' => 'required'
        ]);
        $role = new Role();
        $role->name = $request->name;
        $role->slug = $request->slug;
        $role->description = $request->description; // optional
        $role->level = $request->level; // optional
        $role->save();
        if (count($request->permissions) > 0) {
            foreach ($request->permissions as $val) {
                $role->attachPermission($val);
            }
        }
        Session::flash('success', 'El rol fue registrado exitosamente en el sistema.');
        return redirect()->route('roles.list');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'slug' => 'required|unique:roles,id,' . $request->id,
            'description' => 'required'
        ]);
        $role = Role::find($request->id);
        $role->name = $request->name;
        $role->slug = $request->slug; // optional
        $role->description = $request->description; // optional
        $role->level = $request->level; // optional
        $role->save();
        $role->permissions()->sync([]);
        if (count($request->permissions) > 0) {
            foreach ($request->permissions as $val) {
                $role->attachPermission($val);
            }
        }
        Session::flash('success', 'El rol fue actualizado exitosamente en el sistema.');
        return redirect()->route('roles.list');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $role = Role::findOrFail($id);
        if ($role->delete()) {
            return response()->json(["message" => 'El rol fue eliminado exitosamente.']);
        } else {
            return 'false';
        }
    }

}
