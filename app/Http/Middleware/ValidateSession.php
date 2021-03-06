<?php

namespace App\Http\Middleware;

use App\Modulo;
use App\Privilegio;
use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ValidateSession
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

        if (Session::get('usuario') == '') {
            return redirect('/');
        }
        return $next($request);
    }
}
