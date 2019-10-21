<?php

namespace App\Http\ViewComposers;

use App\Modulo;
use App\Privilegio;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Session;

class MenuComposer
{
    public function compose(View $view)
    {
//        $itemsPadres = Modulo::getListModuleParent();
//        foreach($itemsPadres as $padre)
//        {
//            $hijos =  Modulo::getListModuleChildren($padre->idModule);
//            $padre->hijos = $hijos;
//        }
//        $view->with('modulos',$itemsPadres);

        $perfilId = Session::get('usuario')['usuario_perfil_id'];
//        if($perfilId == ''){
//            return redirect()->intended('/');
//        }
        $modulesPadres = Modulo::getListModuleParent();

        $itemsPadres = [];
        foreach ($modulesPadres as $padre) {
            $privilegio = Privilegio::getPrivilegio($perfilId, $padre->idModule);
            if ($privilegio) {
                $hijos = Modulo::getListModuleChildren($padre->idModule);
                $ModuloHijos = [];
                foreach ($hijos as $hijo) {
                    $privilegio = Privilegio::getPrivilegio($perfilId, $hijo->idModule);
                    if ($privilegio) {
                        $ModuloHijos[] = $hijo;
                    }
                }
                $padre->hijos = $ModuloHijos;
                $itemsPadres[] = $padre;
            }
        }
        $view->with('modulos', $itemsPadres);
    }
}