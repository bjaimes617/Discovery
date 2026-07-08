<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\bait\BaitVentas;
use Carbon\Carbon;

class CheckBaitPortabilidad
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $venta = BaitVentas::where('numero_portar', $request->numero_portabilidad)
            ->orderBy('created_at', 'desc')
            ->get()->last();

        if ($venta) {
            $mesesTranscurridos = Carbon::parse($venta->created_at)->diffInMonths(Carbon::now());

            if ($venta->estatus_id == 6) {
                return $next($request);
            }

            if ($mesesTranscurridos >= 3) {
                return $next($request);
            } else {
                $super = $venta->relationSupervisor != null ?
                    "Supervisor: " . $venta->relationSupervisor->relationUser->nombre_apellido :
                    "Operador: " . $venta->RelationUser->nombre_apellido;
                $fecha = Carbon::parse($venta->created_at)->format('d/m/Y');
                $mensaje = "El número de portabilidad fue registrado hace menos de 3 meses (Fecha: " . $fecha . ", Asociado a: " . $super . ").";
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'error' => $mensaje
                    ], 422);
                }
                return back()->with('error', $mensaje);
            }
        }

        return $next($request);
    }
}
