<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $select = DB::table('tbl_tipo_documento');

        $countSelect = clone $select;
        $rsCount = $countSelect->get();
        $countRegs = count($rsCount);
        $select->orderBy('tdc_id', 'desc')
            ->limit($take)
            ->offset($skip);
        $rows = $select->get();

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
        $tipDocumento = DB::table('tbl_tipo_documento')
            ->where('tdc_id', '=', $idTipDoc)
            ->first();

        if ($idTipDoc == '') {
            return view('tipodoc.tipodoc');
        } else {
            if ($tipDocumento) {
                return view('tipodoc.tipodoc', [
                    'tipDocumento' => $tipDocumento,
                ]);
            } else {
                return redirect()->action('TipoDocumentoController@index');
            }
        }

    }

    public function save(Request $request)
    {
        $error = [];
        if ($request->input('tdc_codigo') == '') {
            $error['tdc_codigo'] = "Debe ingresar codigo del documento";
        }

        if ($request->input('tdc_descripcion') == '') {
            $error['tdc_descripcion'] = "Debe ingresar descripcion del documento";
        }

        if ($request->input('tdc_sigla') == '') {
            $error['tdc_sigla'] = "Debe ingresar sigla del documento";
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        $dataInsert = [
            'tdc_codigo'    => $request->input('tdc_codigo'),
            'tdc_descripcion'    => $request->input('tdc_descripcion'),
            'tdc_orden'    => $request->input('tdc_orden'),
            'tdc_sigla'    => $request->input('tdc_sigla'),
        ];

        if ($request->input('tdc_id') == '') {
            $id = DB::table('tbl_tipo_documento')
                ->insertGetId($dataInsert);
        }else{
            DB::table('tbl_tipo_documento')
                ->where('tdc_id', $request->input('tdc_id'))
                ->update($dataInsert);
            $id = $request->input('tdc_id');
        }

        $result = ['status'=>STATUS_OK,'id'=>$id];

        return response()->json($result);
    }

    public function eliminar(Request $request){
        if ($request->input('id') == '') {
            $res = ['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada'];
            return response()->json($res);
        }
        DB::table('tbl_tipo_documento')
            ->where('tdc_id', $request->input('id'))
            ->delete();

        return response()->json(['status'=>STATUS_OK]);
    }
}
