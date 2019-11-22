<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Visita;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class VisitaController extends Controller
{
    public function index()
    {
        Session::forget('visita');
        return view('visita.main');
    }

    public function GetMainList(Request $request)
    {
        $take = $request->input('take');
        $skip = $request->input('skip');

        $hoy = (new Carbon())->format(MYSQL_DATE_FORMAT);

        $countRegs = Visita::getCountVisita($hoy);
        $rows = Visita::getList($take, $skip,$hoy );

        foreach ($rows as $row) {

            $tool = '
                        <div class="mini ui button left pointing dropdown compact icon circular">
                        <i class="large ellipsis vertical icon"></i>
                        <div class="menu">';

            /*$tool .= '
		                <div class="item ajxEdit" data-idVisita="' . $row->vsta_id . '">
                        <i class="blue edit icon"></i>
		                Modificar
		                </div>';*/

            if ($row->vsta_estado == ST_NUEVO) {

                $row->estado = '<div class="ui label mini grey" > Nueva </div > ';

                $tool .= '
		                <div class="item ajxUpAsignar" data-idVisita="' . $row->vsta_id . '">
                        <i class="green eye icon"></i>
		                Asignar Historia
		                </div>';

                $tool .= '
                            <div class="ui divider"></div>
                            <div class="item ajxDelete" data-idVisita="' . $row->vsta_id . '">
                            <i class="black trash alternate icon"></i>
                            Eliminar
                            </div>';

                $tool .= '
                            </div >
                            </div > ';

            } elseif ($row->vsta_estado == ST_ACTIVO) {

                $row->estado = '<div class="ui label mini yellow" > Asignada </div > ';

                $tool .= '
		                <div class="item ajxUpAtender" data-idVisita="' . $row->vsta_id . '">
                        <i class="violet play icon"></i>
		                Iniciar Atención
		                </div>';

                $tool .= '
                            </div >
                            </div > ';

            } elseif ($row->vsta_estado == ST_INACTIVO) {

                $row->estado = '<div class="ui label mini green" > Atención en Proceso </div > ';

                $tool .= '
		                <div class="item ajxViewHistoria" data-idVisita="' . $row->vsta_id . '">
                        <i class="violet eye icon"></i>
		                Ver Historia
		                </div>';

                $tool .= '
                            </div >
                            </div > ';

            } elseif ($row->vsta_estado == 4) {

                $row->estado = '<div class="ui label mini blue" > Atendido </div > ';
                $tool = '';

            }

            $row->tool = $tool;
        }
        return response()->json(['status' => STATUS_OK, 'data' => ['data' => $rows, 'count' => $countRegs]]);
    }

    public function edit($idVisita = '')
    {
        $clientes = Cliente::getListClientesActive();
        if ($idVisita == '') {
            return view('visita.visita', [
                'clientes' => $clientes,
            ]);
        }

        $rsVisita = Visita::getVisita($idVisita);
        if (!$rsVisita) {
            return redirect()->action('VisitaController@index');
        }
        return view('visita.visita', [
            'rsVisita' => $rsVisita,
            'clientes' => $clientes,
        ]);
    }

    public function save(Request $request)
    {
        $error = [];
        $validator = Validator::make($request->all(), [
            'vsta_cliente_id' => 'required',
            'vsta_motivo' => 'required',
        ]);
        foreach ($validator->errors()->getMessages() as $key => $message) {
            $error[$key] = $message[0];
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        $hoy = (new Carbon())->format(MYSQL_DATE_FORMAT);

        if (!$request->filled('vsta_id')) {
            $endVisita = Visita::getLastVisita($hoy);

            $request->merge(['vsta_ticket_correlativo' => (!$endVisita) ? 1 : intval($endVisita->vsta_ticket_correlativo + 1)]);
            $request->merge(['vsta_estado' => ST_NUEVO]);
            $request->merge(['vsta_fecha_llegada' => Carbon::now() ]);
            $visita = Visita::create($request->all());
            return response()->json(['status' => STATUS_OK, 'id' => $visita->vsta_id]);
        }
        $visita = Visita::updateRow($request);
        return response()->json(['status' => STATUS_OK, 'id' => $visita->vsta_id]);
    }

    public function eliminar(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $request->merge(['vsta_id' => $request->input('id')]);
        $request->merge(['vsta_estado' => ST_ELIMINADO]);
        Visita::updateRow($request);
        return response()->json(['status' => STATUS_OK]);
    }

    public function iniciarAsignacion(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }

        $rsVisita = Visita::getVisita( $request->input('id') );

        Session::put('visita', $rsVisita);

        return response()->json(['status' => STATUS_OK]);
    }

    public function confirmarAsignacion(Request $request)
    {
        if ($request->filled('id')) {
            $request->merge(['vsta_history_found' => Carbon::now() ]);
            $request->merge(['vsta_historia_id' => $request->input('id')]);
        }
        $request->merge(['vsta_estado' => ST_ACTIVO]);
        $visita = Session::get('visita');
        $request->merge(['vsta_id' => $visita->vsta_id ]);

        Visita::updateRow($request);
        Session::forget('visita');

        return response()->json(['status' => STATUS_OK]);
    }

    public function iniciarAtencion(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }

        $request->merge(['vsta_start_atencion' => Carbon::now() ]);
        $request->merge(['vsta_estado' => ST_INACTIVO]);
        $request->merge(['vsta_id' => $request->input('id') ]);

        $rsVisita = Visita::updateRow($request);
        Session::put('visita', $rsVisita);
        $idHistoria = intval($rsVisita->vsta_historia_id);
        return response()->json(['status' => STATUS_OK,'idHistoria' => $idHistoria]);
    }

    public function viewHistoria(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }

        $rsVisita = Visita::getVisita( $request->input('id') );
        Session::put('visita', $rsVisita);
        $idHistoria = intval($rsVisita->vsta_historia_id);

        return response()->json(['status' => STATUS_OK,'idHistoria' => $idHistoria]);
    }
}
