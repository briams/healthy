<?php

namespace App\Http\ViewComposers;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;

class MenuComposer
{
    public function compose(View $view)
    {
//        $perfilId = auth()->user()->usu_perfil_adm;

        $itemsPadres = DB::table('tbl_module')
//            ->join(TABLE_PRIVILEGIOS,TABLE_MENU.'.menu_id','=',TABLE_PRIVILEGIOS.'.privi__menu_id')
//            ->where(TABLE_PRIVILEGIOS.'.privi__perfil_id','=',$perfilId)
            ->where('estado','=',1)
            ->where('is_parent','=',1)
            ->orderBy('orden', 'asc')
            ->get();


        foreach($itemsPadres as $padre)
        {
            $hijos =  DB::table('tbl_module')
                ->where('padre_id', $padre->idModule)
                ->orderBy('orden', 'asc')
                ->get();
            $padre->hijos = $hijos;
        }
        $view->with('modulos',$itemsPadres);
    }
}