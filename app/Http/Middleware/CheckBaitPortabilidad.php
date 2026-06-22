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
            ->first();

        if ($venta) {
            $mesesTranscurridos = Carbon::parse($venta->created_at)->diffInMonths(Carbon::now());

            if ($mesesTranscurridos >= 3) {
                return $next($request);
            } else {
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'error' => 'El número de portabilidad fue registrado hace menos de 3 meses.'
                    ], 422);
                }
                return back()->with('error', 'El número de portabilidad fue registrado hace menos de 3 meses.');
            }
        }

        return $next($request);
    }
}
