<?php

namespace App\Http\Controllers;

use App\TipoServicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TipoServicioController extends Controller
{
    public function index()
    {
        return view('tipservicio.main');
    }

    public function GetMainList(Request $request)
    {
        $take = $request->input('take');
        $skip = $request->input('skip');

        $countRegs = TipoServicio::getCountTipoServicio();
        $rows = TipoServicio::getList($take, $skip);

        foreach ($rows as $row) {
            $tool = '
                        <div class="mini ui button left pointing dropdown compact icon circular">
                        <i class="large ellipsis vertical icon"></i>
                        <div class="menu">';

            $tool .= '
		                <div class="item ajxEdit" data-idTipServ="' . $row->servtip_id . '">
                        <i class="blue edit icon"></i>
		                Modificar
		                </div>';

            $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDelete" data-idTipServ="' . $row->servtip_id . '">
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

    public function edit($idTipoServicio = '')
    {
        if ($idTipoServicio == '') {
            return view('tipservicio.tipservicio');
        }

        $tipoServicio = TipoServicio::getTipoServicio($idTipoServicio);
        if (!$tipoServicio) {
            return redirect()->action('TipoServicioController@index');
        }
        return view('tipservicio.tipservicio', [
            'tipoServicio' => $tipoServicio,
        ]);
    }

    public function save(Request $request)
    {
        $error = [];
        $validator = Validator::make($request->all(), [
            'servtip_nombre' => 'required',
            'servtip_descripcion' => 'required',
        ]);
        foreach ($validator->errors()->getMessages() as $key => $message) {
            $error[$key] = $message[0];
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        if (!$request->filled('servtip_id')) {
            $tipoServicio = TipoServicio::create($request->all());
            return response()->json(['status' => STATUS_OK, 'id' => $tipoServicio->servtip_id]);
        }
        $tipoServicio = TipoServicio::updateRow($request);
        return response()->json(['status' => STATUS_OK, 'id' => $tipoServicio->servtip_id]);
    }

    public function eliminar(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        TipoServicio::deleteTipoServicio($request->input('id'));
        return response()->json(['status' => STATUS_OK]);
    }
}
