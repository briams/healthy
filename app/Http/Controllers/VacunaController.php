<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VacunaController extends Controller
{
    public function index()
    {
        return view('vacuna.main');
    }

    public function GetMainList(Request $request)
    {
        $take = $request->input('take');
        $skip = $request->input('skip');

        $select = DB::table('tbl_vacuna')
            ->where('vac_estado', '>', -1);

        $countSelect = clone $select;
        $rsCount = $countSelect->get();
        $countRegs = count($rsCount);
        $select->orderBy('vac_id', 'desc')
            ->limit($take)
            ->offset($skip);
        $rows = $select->get();

        foreach ($rows as $row) {
            $tool = '
                        <div class="mini ui button left pointing dropdown compact icon circular">
                        <i class="large ellipsis vertical icon"></i>
                        <div class="menu">';

            $tool .= '
		                <div class="item ajxEdit" data-idVac="' . $row->vac_id . '">
                        <i class="blue edit icon"></i>
		                Modificar
		                </div>';

            if ($row->vac_estado == ST_NUEVO) {
                $row->estado = '<div class="ui label mini grey" > Nuevo</div > ';
                $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxUp" data-idVac="' . $row->vac_id . '">
                        <i class="green lock open icon"></i>
		                Activar
		                </div>';
            } elseif ($row->vac_estado == ST_ACTIVO) {
                $row->estado = '<div class="ui label mini green" > Activo</div > ';
                $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDown" data-idVac="' . $row->vac_id . '">
                        <i class="red lock icon"></i>
		                Bloquear
		                </div>';
            } elseif ($row->vac_estado == ST_INACTIVO) {
                $row->estado = '<div class="ui label mini red" > Inactivo</div > ';
                $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxUp" data-idVac="' . $row->vac_id . '">
                        <i class="green lock open icon"></i>
		                Activar
		                </div>';
            }

            $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDelete" data-idVac="' . $row->vac_id . '">
                        <i class="black trash alternate icon"></i>
		                Eliminar
		                </div>';
            $tool .= '
		                </div >
		                </div > ';

            $row->tool = $tool;
        }
        return response()->json(['status' => STATUS_OK, 'data' => ['data' => $rows, 'count' => $countRegs]]);
    }

    public function edit($idVacuna = '')
    {
        $rsVacuna = DB::table('tbl_vacuna')
            ->where('vac_id', '=', $idVacuna)
            ->first();

        if ($idVacuna == '') {
            return view('vacuna.vacuna');
        } else {
            if ($rsVacuna) {
                return view('vacuna.vacuna', [
                    'rsVacuna' => $rsVacuna,
                ]);
            } else {
                return redirect()->action('VacunaController@index');
            }
        }

    }

    public function save(Request $request)
    {
        $error = [];
        if ($request->input('vac_abreviatura') == '') {
            $error['vac_abreviatura'] = "Debe ingresar abreviatura de la vacuna";
        }

        if ($request->input('vac_descripcion') == '') {
            $error['vac_descripcion'] = "Debe ingresar descripcion de la vacuna";
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        $dataInsert = [
            'vac_descripcion'    => $request->input('vac_descripcion'),
            'vac_abreviatura'    => $request->input('vac_abreviatura'),
        ];

        if ($request->input('vac_id') == '') {
            $dataInsert['vac_estado'] = ST_NUEVO;
            $id = DB::table('tbl_vacuna')
                ->insertGetId($dataInsert);
        }else{
            DB::table('tbl_vacuna')
                ->where('vac_id', $request->input('vac_id'))
                ->update($dataInsert);
            $id = $request->input('vac_id');
        }

        $result = ['status'=>STATUS_OK,'id'=>$id];

        return response()->json($result);
    }

    public function bloquear(Request $request){
        if ($request->input('id') == '') {
            $res = ['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada'];
            return response()->json($res);
        }
        DB::table('tbl_vacuna')
            ->where('vac_id', $request->input('id'))
            ->update([ 'vac_estado' => ST_INACTIVO ]);

        return response()->json(['status'=>STATUS_OK]);
    }

    public function activar(Request $request){
        if ($request->input('id') == '') {
            $res = ['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada'];
            return response()->json($res);
        }
        DB::table('tbl_vacuna')
            ->where('vac_id', $request->input('id'))
            ->update([ 'vac_estado' => ST_ACTIVO ]);

        return response()->json(['status'=>STATUS_OK]);
    }

    public function eliminar(Request $request){
        if ($request->input('id') == '') {
            $res = ['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada'];
            return response()->json($res);
        }
        DB::table('tbl_vacuna')
            ->where('vac_id', $request->input('id'))
            ->update([ 'vac_estado' => ST_ELIMINADO ]);

        return response()->json(['status'=>STATUS_OK]);
    }
}
