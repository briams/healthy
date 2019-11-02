<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Historia;
use App\Intervencion;
use App\Mascota;
use App\TipoIntervencion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class IntervencionController extends Controller
{
    public function GetMainList(Request $request)
    {
        $take = $request->input('take');
        $skip = $request->input('skip');
        $idHistoria = $request->input('intervencion_historia_id');

        $countRegs = Intervencion::getCountIntervencion($idHistoria);
        $rows = Intervencion::getList($take, $skip,$idHistoria);

        foreach ($rows as $row) {

            $row->intervencion_fecha = (new Carbon($row->intervencion_fecha))->format('d/m/Y');
            $tipoIntervencion = TipoIntervencion::getTipoIntervencion($row->intervencion_interventip_id);
            $row->intervencion_interventip_id = $tipoIntervencion->intervenciont_nombre;
            $tool = '
                        <div class="mini ui button left pointing dropdown compact icon circular">
                        <i class="large ellipsis vertical icon"></i>
                        <div class="menu">';

            $tool .= '
		                <div class="item ajxEdit" data-idIntervencion="' . $row->intervencion_id . '">
                        <i class="blue edit icon"></i>
		                Modificar
		                </div>';

            $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDelete" data-idIntervencion="' . $row->intervencion_id . '">
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

    public function edit($idHistoria,$idIntervencion = '')
    {
        $rsHistoria = Historia::getHistoria($idHistoria);
        $rsMascota = Mascota::getMascota($rsHistoria->historia_mascota_id);
        $rsCliente = Cliente::getCliente($rsMascota->mascota_cliente_id);
        $tipoIntervencion = TipoIntervencion::getAllList();
        if ($idIntervencion == '') {
            return view('intervencion.intervencion', [
                'tipoIntervencion' => $tipoIntervencion,
                'idHistoria' => $idHistoria,
                'rsMascota' => $rsMascota,
                'rsCliente' => $rsCliente,
            ]);
        }

        $rsIntervencion = Intervencion::getIntervencion($idIntervencion);
        if (!$rsIntervencion) {
            return redirect()->action('IntervencionController@index');
        }
        if($rsIntervencion->intervencion_fecha != '')
            $rsIntervencion->intervencion_fecha = (new Carbon($rsIntervencion->intervencion_fecha))->format('d/m/Y');
        return view('intervencion.intervencion', [
            'rsIntervencion' => $rsIntervencion,
            'tipoIntervencion' => $tipoIntervencion,
            'idHistoria' => $idHistoria,
            'rsMascota' => $rsMascota,
            'rsCliente' => $rsCliente,
        ]);
    }

    public function save(Request $request)
    {
        $error = [];
        $validator = Validator::make($request->all(), [
            'intervencion_interventip_id' => 'required',
            'intervencion_historia_id' => 'required',
            'intervencion_fecha' => 'required',
        ]);
        foreach ($validator->errors()->getMessages() as $key => $message) {
            $error[$key] = $message[0];
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        if ($request->filled('intervencion_fecha')) {
            $parte = explode('/', $request->input('intervencion_fecha'));
            $request->merge(['intervencion_fecha' => (new Carbon($parte[2] . '-' . $parte[1] . '-' . $parte[0]))->format('Y/m/d') ]);
        }

        $rsHistoria = Historia::getHistoria($request->input('intervencion_historia_id'));

        $user = Session::get('usuario');
        $request->merge(['intervencion_usuario' => $user->idUsuario]);

        if (!$request->filled('intervencion_id')) {
            $request->merge(['intervencion_estado' => ST_ACTIVO]);
            $intervencion = Intervencion::create($request->all());
            return response()->json(['status' => STATUS_OK, 'id' => $intervencion->intervencion_id, 'idMascota' => $rsHistoria->historia_mascota_id]);
        }
        $intervencion = Intervencion::updateRow($request);
        return response()->json(['status' => STATUS_OK, 'id' => $intervencion->intervencion_id, 'idMascota' => $rsHistoria->historia_mascota_id]);
    }

    public function eliminar(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $request->merge(['intervencion_id' => $request->input('id')]);
        $request->merge(['intervencion_estado' => ST_ELIMINADO]);
        Intervencion::updateRow($request);
        return response()->json(['status' => STATUS_OK]);
    }
}
