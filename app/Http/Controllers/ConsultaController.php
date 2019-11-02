<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Consulta;
use App\Examen;
use App\Historia;
use App\Mascota;
use App\Sintoma;
use App\TipoExamen;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ConsultaController extends Controller
{
    public function GetMainList(Request $request)
    {
        $take = $request->input('take');
        $skip = $request->input('skip');
        $idHistoria = $request->input('consulta_historia_id');

        $countRegs = Consulta::getCountConsulta($idHistoria);
        $rows = Consulta::getList($take, $skip,$idHistoria);

        foreach ($rows as $row) {

            $row->consulta_fecha_registro = (new Carbon($row->consulta_fecha_registro))->format('d/m/Y');

            $tool = '
                        <div class="mini ui button left pointing dropdown compact icon circular">
                        <i class="large ellipsis vertical icon"></i>
                        <div class="menu">';

            $tool .= '
		                <div class="item ajxEdit" data-idConsulta="' . $row->consulta_id . '">
                        <i class="blue edit icon"></i>
		                Modificar
		                </div>';

            $tool .= '
		                <div class="ui divider"></div>
		                <div class="item ajxDelete" data-idConsulta="' . $row->consulta_id . '">
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

    public function edit($idHistoria,$idConsulta = '')
    {
        Session::forget('listExamenes');
        Session::forget('listSintomas');
        $rsHistoria = Historia::getHistoria($idHistoria);
        $rsMascota = Mascota::getMascota($rsHistoria->historia_mascota_id);
        $rsCliente = Cliente::getCliente($rsMascota->mascota_cliente_id);
        $rsTipoExamen = TipoExamen::getAllList();
        if ($idConsulta == '') {
            return view('consulta.consulta', [
                'idHistoria' => $idHistoria,
                'rsMascota' => $rsMascota,
                'rsCliente' => $rsCliente,
                'rsTipoExamen' => $rsTipoExamen,
            ]);
        }

        $rsConsulta = Consulta::getConsulta($idConsulta);

        $rsExamenes = Examen::getListDetalle($idConsulta);
        $arrayListE = [];
        foreach ($rsExamenes as $index=>$row){
            $arrayListE[$row->examen_exament_id] = [
                'examen_exament_id'      => $row->examen_exament_id,
                'examen_observaciones'       => $row->examen_observaciones,
            ];
        }
        Session::put('listExamenes', $arrayListE);

        $rsSintomas = Sintoma::getListDetalle($idConsulta);
        $arrayListS = [];
        foreach ($rsSintomas as $index=>$row){
            $arrayListS[$row->sintoma_id] = [
                'sint_id'            => $row->sintoma_id,
                'sintoma_nombre'        => $row->sintoma_nombre,
                'sintoma_descripcion'   => $row->sintoma_descripcion,
            ];
        }
        Session::put('listSintomas', $arrayListS);

        if (!$rsConsulta) {
            return redirect()->action('ConsultaController@index');
        }
        return view('consulta.consulta', [
            'rsConsulta' => $rsConsulta,
            'idHistoria' => $idHistoria,
            'rsMascota' => $rsMascota,
            'rsCliente' => $rsCliente,
            'rsTipoExamen' => $rsTipoExamen,
        ]);
    }

    public function save(Request $request)
    {
        $error = [];
        $validator = Validator::make($request->all(), [
            'consulta_historia_id' => 'required',
            'consulta_peso' => 'required',
        ]);
        foreach ($validator->errors()->getMessages() as $key => $message) {
            $error[$key] = $message[0];
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        $rsHistoria = Historia::getHistoria($request->input('consulta_historia_id'));

        $user = Session::get('usuario');
        $request->merge(['consulta_usuario' => $user->idUsuario]);

        if (!$request->filled('consulta_id')) {
            $request->merge(['consulta_estado' => ST_ACTIVO]);
            $request->merge(['consulta_fecha_registro' => Carbon::now() ]);
            $consulta = Consulta::create($request->all());
        }else{
            $consulta = Consulta::updateRow($request);
            Examen::deleteAllExamen($consulta->consulta_id);
            Sintoma::deleteAllSintoma($consulta->consulta_id);
        }
        $examenes = Session::get('listExamenes');
        $sintomas = Session::get('listSintomas');
        if($examenes != ''){
            foreach ($examenes as $index=>$row){
                $row['examen_consulta_id'] = $consulta->consulta_id;
                Examen::create($row);
            }
        }
        if($sintomas != ''){
            foreach ($sintomas as $index=>$row){
                $row['sintoma_consulta_id'] = $consulta->consulta_id;
                Sintoma::create($row);
            }
        }
        Session::forget('listExamenes');
        Session::forget('listSintomas');

        return response()->json(['status' => STATUS_OK, 'id' => $consulta->consulta_id, 'idMascota' => $rsHistoria->historia_mascota_id]);
    }

    public function eliminar(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $request->merge(['consulta_id' => $request->input('id')]);
        $request->merge(['consulta_estado' => ST_ELIMINADO]);
        Consulta::updateRow($request);
        return response()->json(['status' => STATUS_OK]);
    }

    public function GetMainListDetalle(Request $request)
    {
        $sintomasTemporal = Session::get('listSintomas');
        $arraySintomas = [];
        if (count($sintomasTemporal) > 0)
        {
            $sintomasTemporal = (object)$sintomasTemporal;
            foreach($sintomasTemporal as $sintoma)
            {
                $sintoma = (object)$sintoma;
                $array = [];

//                $editar = '<a class="ui button mini circular ajxEditIngreso icon " data-almingid="'.$producto->idproducto.'" ><i class="icon write"></i></a>';
                $eliminar = '<div class="ui button medium red circular ajxDelete inverted icon" data-idSintoma="' . $sintoma->sint_id . '"><i class="icon trash alternate"></i></div>';

                $array['eliminar'] = $eliminar;

                $array['sintoma_nombre'] = $sintoma->sintoma_nombre;

                $array['sintoma_descripcion'] = $sintoma->sintoma_descripcion;

                $arraySintomas[] = (object)$array;
            }
        }

        return response()->json(['status' => STATUS_OK, 'data' => ['data' => $arraySintomas, 'count' => count($arraySintomas)]]);
    }

    public function addDetalle(Request $request)
    {
        $error = [];
        $validator = Validator::make($request->all(), [
            'sintoma_nombre' => 'required',
            'sintoma_descripcion' => 'required',
        ]);
        foreach ($validator->errors()->getMessages() as $key => $message) {
            $error[$key] = $message[0];
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        $uuid = Str::uuid()->toString();
        $sintomas = Session::get('listSintomas');
        $request->merge(['sint_id' => $uuid]);
        $sintomas[$uuid] = $request->all();

        Session::put('listSintomas', $sintomas);

        return response()->json(['status' => STATUS_OK]);
    }

    public function removeDetalle(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $sintomas = Session::get('listSintomas');
        $idSintoma = $request->input('id');
        foreach($sintomas as $index =>$item)
        {
            if($item['sint_id'] == $idSintoma)
            {
                unset($sintomas[$idSintoma]);
            }
        }
        Session::put('listSintomas', $sintomas);
        return response()->json(['status' => STATUS_OK]);
    }

    public function GetMainListExamen(Request $request)
    {
        $examenesTemporal = Session::get('listExamenes');
        $arrayExamenes = [];
        if (count($examenesTemporal) > 0)
        {
            $examenesTemporal = (object)$examenesTemporal;
            foreach($examenesTemporal as $examen)
            {
                $examen = (object)$examen;
                $array = [];

//                $editar = '<a class="ui button mini circular ajxEditIngreso icon " data-almingid="'.$producto->idproducto.'" ><i class="icon write"></i></a>';
                $eliminar = '<div class="ui button medium red circular ajxDelete inverted icon" data-idExamen="' . $examen->examen_exament_id . '"><i class="icon trash alternate"></i></div>';

                $array['eliminar'] = $eliminar;
                $rsTipoExamen = TipoExamen::getTipoExamen($examen->examen_exament_id);
                $array['examen_exament_id'] = $rsTipoExamen->exament_nombre;

                $array['examen_observaciones'] = $examen->examen_observaciones;

                $arrayExamenes[] = (object)$array;
            }
        }

        return response()->json(['status' => STATUS_OK, 'data' => ['data' => $arrayExamenes, 'count' => count($arrayExamenes)]]);
    }

    public function addExamen(Request $request)
    {
        $error = [];
        $validator = Validator::make($request->all(), [
            'examen_exament_id' => 'required',
        ]);
        foreach ($validator->errors()->getMessages() as $key => $message) {
            $error[$key] = $message[0];
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        $idExamen = $request->input('examen_exament_id');

        $examenes = Session::get('listExamenes');

        $examenes[$idExamen] = $request->all();

        Session::put('listExamenes', $examenes);

        return response()->json(['status' => STATUS_OK]);
    }

    public function removeExamen(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $examenes = Session::get('listExamenes');
        $idExamen = $request->input('id');
        foreach($examenes as $index =>$item)
        {
            if($item['examen_exament_id'] == $idExamen)
            {
                unset($examenes[$idExamen]);
            }
        }
        Session::put('listExamenes', $examenes);
        return response()->json(['status' => STATUS_OK]);
    }
}
