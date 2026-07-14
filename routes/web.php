<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;

#no se declara la ruta completa, el resource ya construye hasta controller la ruta de acceso#
use \Claro\MasivosController as ClaroMasivos;
use \Claro\PymesController as ClaroPymes;

use App\Http\Controllers\Bait\BaitController;
use App\Http\Controllers\Bait\Backoffice;
use App\Http\Controllers\Bait\UploadsController;

use App\Http\Controllers\Renovaciones\RenovacionesController;
use App\Http\Controllers\Renovaciones\ReportesController;
#Controllador de Google Sheet Api
use App\Http\Controllers\Api\GoogleApi;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['guest']], function () {
    Auth::routes(['verify' => false, 'login' => false, 'register' => false]);
});

//LOGIN
Route::get('/',         [LoginController::class, 'index'])->name('login');
Route::post('signin',   [LoginController::class, 'signin'])->name('signin');
//VALIDACION TWO FACTORS
Route::get('/twofactor/{user}',     [LoginController::class, 'fa2'])->name('login.fa2');
Route::post('/twofactor/{user}', [LoginController::class, 'login2FA'])->name('auth.fa2');

//ROUTES
Route::group(['middleware' => ['auth', 'activity']], function () {
    //DASHBOARD
    Route::get('dashboard',                                     [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard/bait',                                [DashboardController::class, 'bait'])->name('dashboard.bait');
    Route::post('dashboard/bait/data',                          [DashboardController::class, 'bait'])->name('dashboard.bait.data');
    Route::post('dashboard/bait/asignados-sinventas',           [DashboardController::class, 'asignadossinventas'])->name('dashboard.bait.sin-ventas');
    //LOGOUT
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');    //ROLES
    Route::get('/roles/',           [RoleController::class, 'index'])->middleware('permission:roles.view')->name('roles.list');
    Route::post("/roles/alls",      [RoleController::class, 'getRoles'])->middleware('permission:roles.view')->name('roles.get');
    Route::delete('/roles/{role}',  [RoleController::class, 'destroy'])->middleware('permission:roles.delete')->name('roles.destroy');
    Route::post("/roles/create",    [RoleController::class, 'store'])->middleware('permission:roles.create')->name('roles.store');
    Route::post("/roles/",          [RoleController::class, 'update'])->middleware('permission:roles.edit')->name('roles.update');
    //PERMISSIONS
    Route::get('/permisos/',        [PermissionController::class, 'index'])->middleware('permission:permissions.view')->name('permisos.list');
    Route::post("/permisos/alls",   [PermissionController::class, 'getPermissions'])->middleware('permission:permissions.view')->name('permisos.get');
    Route::delete('/permisos/{permiso}', [PermissionController::class, 'destroy'])->middleware('permission:permissions.delete')->name('permisos.destroy');
    Route::post("/permisos/create", [PermissionController::class, 'store'])->middleware('permission:permissions.create')->name('permisos.store');
    Route::post("/permisos/",       [PermissionController::class, 'update'])->middleware('permission:permissions.edit')->name('permisos.update');
    //USERS MODULE
    Route::get('/user/',            [UserController::class, 'index'])->middleware('permission:users.view')->name('user.list');
    Route::post("/user/alls",       [UserController::class, 'getUsers'])->middleware('permission:users.view')->name('user.get');
    Route::delete('/user/{user}',   [UserController::class, 'destroy'])->middleware('permission:users.delete')->name('user.destroy');
    Route::get("/user/create",      [UserController::class, 'create'])->middleware('permission:users.create')->name('user.create');
    Route::post("/user/create",     [UserController::class, 'store'])->middleware('permission:users.create')->name('user.store');
    //chequear usuario y email
    Route::post("/user/checkU",     [UserController::class, 'checkUsername'])->name('user.checkusername');
    Route::post("/user/checkE",     [UserController::class, 'checkEmail'])->name('user.checkemail');
    Route::post("/user/checkN",     [UserController::class, 'checkNumeroEmpleado'])->name('user.checknumeroempleado');
    Route::get("/user/{user}/edit", [UserController::class, 'edit'])->middleware('permission:users.edit')->name('user.edit');
    Route::put("/user/{user}",      [UserController::class, 'update'])->middleware('permission:users.edit')->name('user.update');
    Route::get('/user/change',      [UserController::class, 'changePassword'])->name('user.change');
    Route::post("/user/checkcurrentpassword", [UserController::class, 'checkCurrentPassword'])->name('user.checkcurrentpassword');
    Route::post("/user/checknewpassword",     [UserController::class, 'checkNewPassword'])->name('user.checknewpassword');
    Route::post("/user/password",             [UserController::class, 'updatePassword'])->name('user.password.update');
    Route::get('/user/profile',               [UserController::class, 'profile'])->name('user.profile');
    Route::post('usuarios/add/permissions/',  [UserController::class, 'AddPermissionsAditionals'])->middleware('permission:users.create')->name('users.permissions.add');
    //carga masiva
    Route::get("/user/massive",               [UserController::class, 'cargaMasiva'])->middleware('permission:users.massive')->name('user.massive');
    Route::post("/user/massive",              [UserController::class, 'import'])->middleware('permission:users.massive')->name('user.import');
    //actualizacion masiva
    Route::get("/user/massive/update",  [UserController::class, 'actualizacionMasiva'])->middleware('permission:users.massive')->name('user.update.massive');
    Route::post("/user/massive/update", [UserController::class, 'importUpdate'])->middleware('permission:users.massive')->name('user.update.import');
    //reporte personal global
    Route::get("/user/report",  [UserController::class, 'reporte'])->middleware('permission:users.report')->name('user.report');
    Route::post("/user/report", [UserController::class, 'export'])->middleware('permission:users.report')->name('user.generate');

    //COSTARICA MASIVOS
    Route::controller(ClaroMasivos::class)->group(function () {
        Route::get('claro/masivos',                             'index')->name('claro.masivos.index');
        Route::post('claro/masivos/search',                     'search')->name('claro.masivos.search');
        Route::get('claro/masivos/{id}/edit',                   'edit')->name('claro.masivos.edit');
        Route::put('claro/masivos/{id}/update',                 'update')->name('claro.masivos.update');
        Route::delete('claro/masivos/{id}/delete',              'destroy')->name('claro.masivos.delete');

        Route::get('claro/masivos/create',                          'create')->name('claro.masivos.create');
        Route::post('claro/masivos/get/planes',                     'getPlanes')->name('claro.masivos.getPlanes');
        Route::post('claro/masivos/store',                          'store')->name('claro.masivos.store');

        Route::get('claro/masivos/auditoria',                       'auditoriaIndex')->middleware('permission:claro.masivos.auditoria')->name('claro.masivos.auditoriaIndex');
        Route::post('claro/masivos/auditoria/search',               'auditoriaSearch')->name('claro.masivos.auditoriaSearch');
        Route::post('claro/masivos/auditoria/seguimientos/items',   'seguimientosItems')->withoutMiddleware(['activity'])->name('claro.masivos.seguimientosItems');
        Route::put('claro/masivos/auditoria/seguimientos/store',    'seguimientosStore')->name('claro.masivos.seguimientosStore');
        Route::get('claro/masivos/auditoria/{id}/edit',             'auditoriaEdit')->middleware('permission:claro.masivos.auditoria')->name('claro.masivos.audit');
        Route::put('claro/masivos/auditoria/{id}/storage',          'auditoriaStore')->name('claro.masivos.auditoriaStore');

        Route::get('claro/masivos/reportes',                        'ReportesIndex')->middleware('permission:claro.masivos.auditoria')->name('claro.masivos.reportesIndex');
        Route::post('claro/masivos/reportes/data',                  'ReportesData')->name('claro.masivos.reportesData');
    });

    Route::controller(ClaroPymes::class)->group(function () {
        Route::get('claro/pymes', 'index')->name('claro.pymes.index');
        Route::post('claro/pymes/search', 'search')->name('claro.pymes.search');
        Route::get('claro/pymes/{id}/edit', 'edit')->name('claro.pymes.edit');
        Route::put('claro/pymes/{id}/update', 'update')->name('claro.pymes.update');
        Route::delete('claro/pymes/{id}/delete', 'destroy')->name('claro.pymes.delete');

        Route::get('claro/pymes/create', 'create')->name('claro.pymes.create');
        Route::post('claro/pymes/get/planes', 'getPlanes')->name('claro.pymes.getPlanes');
        Route::post('claro/pymes/store', 'store')->name('claro.pymes.store');

        Route::get('claro/pymes/auditoria',           'auditoriaIndex')->name('claro.pymes.auditoriaIndex');
        Route::post('claro/pymes/auditoria/search',   'auditoriaSearch')->name('claro.pymes.auditoriaSearch');
        Route::post('claro/pymes/auditoria/seguimientos/items',   'seguimientosItems')->withoutMiddleware(['activity'])->name('claro.pymes.seguimientosItems');
        Route::put('claro/pymes/auditoria/seguimientos/store',    'seguimientosStore')->name('claro.pymes.seguimientosStore');
        Route::get('claro/pymes/auditoria/{id}/edit', 'auditoriaEdit')->name('claro.pymes.audit');
        Route::put('claro/pymes/auditoria/{id}/storage',  'auditoriaStore')->name('claro.pymes.auditoriaStore');

        Route::get('claro/pymes/reportes',                        'ReportesIndex')->middleware('permission:claro.masivos.auditoria')->name('claro.pymes.reportesIndex');
        Route::post('claro/pymes/reportes/data',                  'ReportesData')->name('claro.pymes.reportesData');
    });

    Route::controller(BaitController::class)->group(function () {
        Route::get('bait',                  'index')->middleware('permission:bait.ventas.index')->name('bait.index');
        Route::post('bait/search',          'search')->name('bait.search');

        Route::post("bait/check/portabilidad",  "BaitNumeroPortarCheck")->name("portabilidad.bait.check");

        Route::get('bait/create',           'create')->middleware('permission:bait.ventas.create')->name('bait.create');
        Route::post('bait/store',           'store')->middleware('permission:bait.ventas.create')->name('bait.store');
        Route::get('bait/{id}/edit',        'edit')->middleware('permission:bait.edit')->name('bait.edit');
        Route::put('bait/{id}/update',      'update')->middleware('permission:bait.edit')->name('bait.update');
        Route::delete('bait/{id}/delete',   'destroy')->middleware('permission:bait.destroy')->name('bait.delete');
        Route::post('bait/get/municipio',   'GetMunicipio')->name('bait.getMunicipio');
        Route::post('bait/get/tiendas',     'GetTiendas')->name('bait.getTiendas');
    });

    Route::controller(Backoffice::class)->group(function () {
        Route::get('bait/backoffice',                       'index')->middleware('permission:bait.backoffice.index')->name('bait.backoffice.index');
        Route::post('bait/backoffice/search',               'SearchVentasFVC')->name('bait.backoffice.search');
        Route::get('bait/backoffice/{id}/edit',             'edit')->name('bait.backoffice.edit');
        Route::put('bait/backoffice/{id}/update',           'update')->name('bait.backoffice.update');

        Route::get('bait/backoffice/postventa',             'IndexPostventa')->middleware('permission:bait.backoffice.postventas')->name('bait.backoffice.postventa');
        Route::put("bait/unlock/seguimientos",              'BaitUnlockSeguimientos')->name("bait.unlock.seguimientos");
        Route::post('bait/backoffice/postventa/search',     'SearchPostventa')->middleware('permission:bait.backoffice.postventas')->name('bait.backoffice.postventa.search');
        Route::post('bait/backoffice/postventa/historico',  'ShowHistoricos')->middleware('permission:bait.backoffice.postventas')->name('bait.backoffice.postventa.historico');
        Route::put('bait/backoffice/postventa/update/{id}',     'ShowModalUpdate')->middleware('permission:bait.backoffice.postventas')->name('bait.backoffice.postventa.update');
        Route::delete('bait/backoffice/postventa/delete/{id}',  'EliminarVenta')->middleware('permission:bait.backoffice.postventas')->name('bait.backoffice.postventa.delete');
    });

    Route::controller(UploadsController::class)->group(function () {
        #carga de seguimientos masivos
        Route::get('bait/uploads/seguimientos',             'IndexSeguimientosMasivos')->middleware('permission:bait.backoffice.uploadcm')->name('bait.uploads.seguimientos.index');
        Route::post('bait/uploads/seguimientos/store',      'UploadSeguimientosMasivos')->name('bait.uploads.seguimientos.store');
        #ruta CM concentra
        Route::get('bait/uploads/concentra',                'IndexCMConcentra')->middleware('permission:bait.backoffice.uploadcm')->name('bait.uploads.concentra.index');
        Route::post('bait/uploads/concentra/store',         'StoreCMConcentra')->name('bait.uploads.concentra.store');
        #reportes
        Route::get('bait/reportes',             'IndexReportes')->middleware('permission:bait.reportes')->name('bait.reportes.index');
        Route::post('bait/reportes/download',   'DownloadReportes')->name('bait.reportes.download');
    });

    Route::controller(RenovacionesController::class)->group(function () {
        #carga de seguimientos masivos
        Route::get('renovaciones/create',             'create')->middleware('permission:renovaciones.create')->name('renovaciones.create');
        Route::post('renovaciones/store',             'store')->middleware('permission:renovaciones.create')->name('renovaciones.store');
        Route::post("renovaciones/check/dn",          'checkOrderOnix')->name("renovaciones.check.dn");

        Route::get('renovaciones/index',                'index')->middleware('permission:renovaciones.index')->name('renovaciones.index');
        Route::post('renovaciones/search',              'search')->middleware('permission:renovaciones.index')->name('renovaciones.search');
        Route::get('renovaciones/edit/{id}',            'edit')->middleware('permission:renovaciones.edit')->name('renovaciones.edit');
        Route::put('renovaciones/{id}/update',          'update')->middleware('permission:renovaciones.edit')->name('renovaciones.update');
        Route::delete('renovaciones/{id}/delete',       'delete')->middleware('permission:renovaciones.delete')->name('renovaciones.delete');

        Route::get('renovaciones/import/seguimientos',   'indexImport')->middleware('permission:renovaciones.import')->name('renovaciones.import.index');
        Route::post('renovaciones/import/seguimientos',  'StorageImport')->middleware('permission:renovaciones.import')->name('renovaciones.import.storage');

        Route::get('renovaciones/reportes',             'IndexReportes')->middleware('permission:renovaciones.reportes')->name('renovaciones.reportes.index');
        Route::post('renovaciones/reportes/download',   'DownloadReportes')->name('renovaciones.reportes.download');
    });

    Route::controller(ReportesController::class)->group(function () {
        Route::get('renovaciones/export',             'index')->name('renovaciones.export.index');
        Route::post('renovaciones/export/download',   'store')->name('renovaciones.export.download');
    });

    Route::get('google/api',        [GoogleApi::class, 'show'])->name('google.api');
    Route::get('google/api/store',  [GoogleApi::class, 'store'])->name('google.api.store');
});
