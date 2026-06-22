<?php

namespace App\Http\Middleware;

use Closure;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class CheckPassword
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (auth()->user()->estatus_id == 3) {
            Session::put('password', 'Inicio de Sesión por primera vez');
            return redirect(RouteServiceProvider::CHANGEPASSWORD);
        } else {
            $start_date = Carbon::create(auth()->user()->password_updated_at);
            $end_date = Carbon::now();
            $different_days = $start_date->diffInDays($end_date);

            if ($different_days >= config('app.daysrestore')) {
                Session::put('password', 'Su contraseña ha expirado');
                return redirect(RouteServiceProvider::CHANGEPASSWORD);
            } else {
                return $next($request);
            }
        }
    }
}
