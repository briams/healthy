<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\TipoDocumento;
use App\Ubigeo;
use App\Rules\Emailvalidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;
use Peru\Http\ContextClient;
use Peru\Jne\{Dni, DniParser};
use Peru\Sunat\{HtmlParser, Ruc, RucParser};

use Carbon\Carbon;

class ClienteController extends Controller
{
    public function index()
    {
        return view('cliente.main');
    }

    public function GetMainList(Request $request)
    {
        $take = $request->input('take');
        $skip = $request->input('skip');

        $countRegs = Cliente::getCountCliente();
        $rows = Cliente::getList($take, $skip);

        foreach ($rows as $row) {
            $tool = '
                        <div class="mini ui button left pointing dropdown compact icon circular">
                        <i class="large ellipsis vertical icon"></i>
                        <div class="menu">';

            $tool .= '
		                <div class="item ajxEdit" data-idCli="' . $row->cliente_id . '">
                        <i class="blue edit icon"></i>
		                Modificar
		                </div>';

            if ($row->cliente_estado == ST_NUEVO) {
                $row->estado = '<div class="ui label mini grey" > Nuevo</div > ';
                $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxUp" data-idCli="' . $row->cliente_id . '">
                        <i class="green lock open icon"></i>
		                Activar
		                </div>';
            } elseif ($row->cliente_estado == ST_ACTIVO) {
                $row->estado = '<div class="ui label mini green" > Activo</div > ';
                $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDown" data-idCli="' . $row->cliente_id . '">
                        <i class="red lock icon"></i>
		                Bloquear
		                </div>';
            } elseif ($row->cliente_estado == ST_INACTIVO) {
                $row->estado = '<div class="ui label mini red" > Inactivo</div > ';
                $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxUp" data-idCli="' . $row->cliente_id . '">
                        <i class="green lock open icon"></i>
		                Activar
		                </div>';
            }

            $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDelete" data-idCli="' . $row->cliente_id . '">
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

    public function edit($idCliente = '')
    {
        $ubigeo = Ubigeo::getList();
        $tipDocs = TipoDocumento::getListDoc();
        if ($idCliente == '') {
            return view('cliente.cliente', [
                'ubigeo' => $ubigeo,
                'tipDocs' => $tipDocs,
            ]);
        }

        $rsCliente = Cliente::getCliente($idCliente);
        if (!$rsCliente) {
            return redirect()->action('ClienteController@index');
        }
        if($rsCliente->cliente_fecha_nacimiento != '')
        $rsCliente->cliente_fecha_nacimiento = (new Carbon($rsCliente->cliente_fecha_nacimiento))->format('d/m/Y');
        return view('cliente.cliente', [
            'rsCliente' => $rsCliente,
            'ubigeo' => $ubigeo,
            'tipDocs' => $tipDocs,
        ]);
    }

    public function save(Request $request)
    {
        $error = [];
        $validator = Validator::make($request->all(), [
            'cliente_nombres' => 'required',
            'cliente_direccion' => 'required',
            'cliente_telefono' => 'required',
            'cliente_email' => ['required', new Emailvalidation],
            'cliente_tipo_documento' => 'required',
            'cliente_nro_documento' => 'required',
            'cliente_ubigeo' => 'required',
        ]);
        foreach ($validator->errors()->getMessages() as $key => $message) {
            $error[$key] = $message[0];
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        if ($request->input('sigla') == 'RUC') {
            if (!(strlen($request->input('cliente_nro_documento')) == 11)) {
                $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'El nro de RUC debe contar con 11 digitos'];
                return response()->json($res);
            }
        }

        if ($request->input('sigla') == 'DNI') {
            if (!(strlen($request->input('cliente_nro_documento')) == 8)) {
                $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'El nro de DNI debe contar con 8 digitos'];
                return response()->json($res);
            }
        }

        if ($request->filled('cliente_fecha_nacimiento')) {
            $parte = explode('/', $request->input('cliente_fecha_nacimiento'));
            $request->merge(['cliente_fecha_nacimiento' => (new Carbon($parte[2] . '-' . $parte[1] . '-' . $parte[0]))->format('Y/m/d') ]);
        }
        $cliente_fullname = $request->input('cliente_nombres') . (($request->input('cliente_apellidos') != '') ? ' ' . $request->input('cliente_apellidos') : '');
        $request->merge(['cliente_fullname' => $cliente_fullname]);

        if (!$request->filled('cliente_id')) {
            $request->merge(['cliente_estado' => ST_NUEVO]);
            $request->merge(['cliente_registro_ts' => Carbon::now() ]);
            $cliente = Cliente::create($request->all());
            return response()->json(['status' => STATUS_OK, 'id' => $cliente->cliente_id]);
        }
        $cliente = Cliente::updateRow($request);
        return response()->json(['status' => STATUS_OK, 'id' => $cliente->cliente_id]);
    }

    public function bloquear(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $request->merge(['cliente_id' => $request->input('id')]);
        $request->merge(['cliente_estado' => ST_INACTIVO]);
        Cliente::updateRow($request);
        return response()->json(['status' => STATUS_OK]);
    }

    public function activar(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $request->merge(['cliente_id' => $request->input('id')]);
        $request->merge(['cliente_estado' => ST_ACTIVO]);
        Cliente::updateRow($request);
        return response()->json(['status' => STATUS_OK]);
    }

    public function eliminar(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $request->merge(['cliente_id' => $request->input('id')]);
        $request->merge(['cliente_estado' => ST_ELIMINADO]);
        Cliente::updateRow($request);
        return response()->json(['status' => STATUS_OK]);
    }

    public function searchDoc(Request $request)
    {

        $sigla = $request->input('sigla');
        $nroDoc = $request->input('nroDoc');
        if ($sigla == 'DNI') {
            $cs = new Dni(new ContextClient(), new DniParser());
            $person = $cs->get($nroDoc);
            if (!$person) {
                $res = ['status' => STATUS_FAIL, 'msg' => 'Dni invalido'];
                return response()->json($res);
            } else {
                $res = ['status' => STATUS_OK, 'msg' => 'Dni Encontrado', 'nombre' => $person->nombres, 'apellido' => $person->apellidoPaterno . ' ' . $person->apellidoMaterno, 'direccion' => ''];
                return response()->json($res);
            }
        } else if ($sigla == 'RUC') {
            $cs = new Ruc(new ContextClient(), new RucParser(new HtmlParser()));

            $company = $cs->get($nroDoc);
            if (!$company) {
                $res = ['status' => STATUS_FAIL, 'msg' => 'Ruc invalido'];
                return response()->json($res);
            } else {
                $res = ['status' => STATUS_OK, 'msg' => 'Razon Social Encontrada', 'nombre' => $company->razonSocial, 'apellido' => '', 'direccion' => $company->direccion];
                return response()->json($res);
            }
        }
    }
}
