<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use jeremykenedy\LaravelRoles\Models\Role;
use jeremykenedy\LaravelRoles\Models\Permission;

class PermissionController extends Controller {

    public function __construct() {
        $this->middleware(['auth','verified','check.password']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return response()->view('permission/index');
    }

    /**
     * Get all roles.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getPermissions(Request $request) {
        if ($request->ajax()) {
            $data = array();
            $permisos = Permission::orderBy('created_at', 'desc')->get();

            foreach ($permisos as $rs) {
                $permiso = array();
                $permiso['Nombre'] = $rs->name;
                $permiso['Accion'] = $rs->slug;
                $permiso['Descripci&oacute;n'] = $rs->description;
                $permiso['Creado En'] = date('d/m/Y H:i:s', strtotime($rs->created_at));
                

                $actionsHtml = "
                                <a class='btn btn-sm btn-clean btn-icon btn-icon-md editpermission' href='javascript:void(0)' data-id='" . $rs->id . "' data-name='" . $rs->name . "' data-slug='" . $rs->slug . "' data-description='" . $rs->description . "' data-level='" . $rs->level . "' title='Editar'><i class='la la-edit'></i></a>
                                <a class='btn btn-sm btn-clean btn-icon btn-icon-md deleterow' href='javascript:void(0)' data-toggle='modal' title='Eliminar' data-url='permisos/" . $rs->id . "' data-title='" . $rs->name . "'><i class='la la-trash'></i></a>
";
                $permiso['Acciones'] = $actionsHtml;


                $data[] = $permiso;
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
            'slug' => 'required|unique:permissions',
            'description' => 'required'
        ]);
        $permission= new Permission();
        $permission->name = $request->name;
        $permission->slug = $request->slug;
        $permission->description = $request->description; // optional
        $permission->save();

        Session::flash('success', 'El permiso fue registrado exitosamente en el sistema.');
        return redirect()->route('permisos.list');
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
            'slug' => 'required|unique:permissions,id,' . $request->id,
            'description' => 'required'
        ]);
        $permission = Permission::find($request->id);
        $permission->name = $request->name;
        $permission->slug = $request->slug; // optional
        $permission->description = $request->description; // optional
        $permission->save();

        Session::flash('success', 'El permiso fue actualizado exitosamente en el sistema.');
        return redirect()->route('permisos.list');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $permiso = Permission::findOrFail($id);
        if ($permiso->delete()) {
            return response()->json(["message" => 'El permiso fue eliminado exitosamente.']);
        } else {
            return 'false';
        }
    }

}
