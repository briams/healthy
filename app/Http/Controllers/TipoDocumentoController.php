<?php

namespace App\Http\Controllers;

use App\TipoDocumento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TipoDocumentoController extends Controller
{
    public function index()
    {
        return view('tipodoc.main');
    }

    public function GetMainList(Request $request)
    {
        $take = $request->input('take');
        $skip = $request->input('skip');

        $countRegs = TipoDocumento::getCountTipDoc();
        $rows = TipoDocumento::getList($take, $skip);

        foreach ($rows as $row) {
            $tool = '
                        <div class="mini ui button left pointing dropdown compact icon circular">
                        <i class="large ellipsis vertical icon"></i>
                        <div class="menu">';

            $tool .= '
		                <div class="item ajxEdit" data-idTipDoc="' . $row->tdc_id . '">
                        <i class="blue edit icon"></i>
		                Modificar
		                </div>';

            $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDelete" data-idTipDoc="' . $row->tdc_id . '">
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

    public function edit($idTipDoc = '')
    {
        if ($idTipDoc == '') {
            return view('tipodoc.tipodoc');
        }

        $tipDocumento = TipoDocumento::getTipDoc($idTipDoc);
        if (!$tipDocumento) {
            return redirect()->action('TipoDocumentoController@index');
        }
        return view('tipodoc.tipodoc', [
            'tipDocumento' => $tipDocumento,
        ]);
    }

    public function save(Request $request)
    {
        $error = [];
        $validator = Validator::make($request->all(), [
            'tdc_codigo' => 'required',
            'tdc_descripcion' => 'required',
            'tdc_sigla' => 'required',
        ]);
        foreach ($validator->errors()->getMessages() as $key => $message) {
            $error[$key] = $message[0];
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        if (!$request->filled('tdc_id')) {
            $tipDoc = TipoDocumento::create($request->all());
            return response()->json(['status' => STATUS_OK, 'id' => $tipDoc->tdc_id]);
        }
        $tipDoc = TipoDocumento::updateRow($request);
        return response()->json(['status' => STATUS_OK, 'id' => $tipDoc->tdc_id]);

    }

    public function eliminar(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        TipoDocumento::deleteTipDoc($request->input('id'));
        return response()->json(['status' => STATUS_OK]);
    }
}
