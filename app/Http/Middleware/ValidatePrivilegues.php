<?php

namespace App\Http\Middleware;

use App\Modulo;
use App\Privilegio;
use Closure;
use Illuminate\Support\Facades\Session;

class ValidatePrivilegues
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
        $url = explode(BASE_WEB_ROOT,$request->url());
        $url = explode('/',end($url));
        $idModule = Modulo::getIdModule($url[0]);
        $session = Session::get('usuario');
        $validate = Privilegio::getPrivilegio($session->usuario_perfil_id,$idModule);
        if( $validate == ''){
            abort(401);
        }
//        Log::info($validate);
        return $next($request);
    }
}
