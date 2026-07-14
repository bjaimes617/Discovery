<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaitApiController;
use App\Http\Controllers\Api\RespondioController;
use App\Http\Controllers\Api\RenovacionesApiController;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

#########################BAIT###################################
//api para recibir informacion desde respondeo BAIT  / CONCENTRA / RENOVACIONES
Route::post('/respondio/incoming/data',     [RespondioController::class, 'store']);

//api para enviar los estados AGENTES IA BAIT
Route::post('/bait/send/estados',      [BaitApiController::class, 'SendEstadosBait']);
//api Storage Ventas desde los AGENTES IA - token por usuario
Route::middleware(['auth:sanctum'])->post('/bait/incoming/sales',  [BaitApiController::class, 'StoreVentas']);
Route::put('/bait/update/sales',    [BaitApiController::class, 'updateVenta']);
//api  de totalizacion de ciclos de vida y contactos BAIT
Route::middleware(['auth:sanctum'])->post('/bait/show/data', [BaitApiController::class, 'show']);



#########################3RENOVACIONES###################################
// api reporteria de Renovaciones Ventas Unicas
Route::middleware(['auth:sanctum'])->post('/renovaciones/show/data', [RenovacionesApiController::class, 'show']);
