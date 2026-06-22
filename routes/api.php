<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaitApiController;
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

//api para recibir informacion desde respondeo
Route::post('/bait/incoming/data',     [BaitApiController::class, 'store']);
//api para enviar los estados
Route::post('/bait/send/estados',      [BaitApiController::class, 'SendEstadosBait']);

Route::middleware(['auth:sanctum'])->post('/bait/incoming/sales',  [BaitApiController::class, 'StoreVentas']);
Route::put('/bait/update/sales',    [BaitApiController::class, 'updateVenta']);

//api  de totalizacion de ciclos de vida y contactos
Route::middleware(['auth:sanctum'])->post('/bait/show/data', [BaitApiController::class, 'show']);
