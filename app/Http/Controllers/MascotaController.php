<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Especie;
use App\Historia;
use App\Mascota;
use App\Raza;
use App\Sexo;
use App\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class MascotaController extends Controller
{
    public function index()
    {
        return view('mascota.main');
    }

    public function GetMainList(Request $request)
    {
        $take = $request->input('take');
        $skip = $request->input('skip');

        $countRegs = Mascota::getCountMascota();
        $rows = Mascota::getList($take, $skip);

        foreach ($rows as $row) {
            $tool = ' 
                        <div class="mini ui button left pointing dropdown compact icon circular"> 
                        <i class="large ellipsis vertical icon"></i> 
                        <div class="menu">';

            $tool .= ' 
                        <div class="item ajxEdit" data-idMasc="' . $row->mascota_id . '"> 
                        <i class="blue edit icon"></i> 
                        Modificar 
                        </div>';

            $tool .= ' 
                        <div class="item ajxHistoria" data-idMasc="' . $row->mascota_id . '"> 
                        <i class="blue file icon"></i> 
                        Historia
                        </div>';

            if ($row->mascota_estado == ST_NUEVO) {
                $row->estado = '<div class="ui label mini grey" > Nuevo</div > ';
                $tool .= ' 
                            <div class="ui divider"></div> 
                            <div class="item ajxUp" data-idMasc="' . $row->mascota_id . '"> 
                            <i class="green lock open icon"></i> 
                            Activar 
                            </div>';
            } elseif ($row->mascota_estado == ST_ACTIVO) {
                $row->estado = '<div class="ui label mini green" > Activo</div > ';
                $tool .= ' 
                            <div class="ui divider"></div> 
                            <div class="item ajxDown" data-idMasc="' . $row->mascota_id . '"> 
                            <i class="red lock icon"></i> 
                            Bloquear 
                            </div>';
            } elseif ($row->mascota_estado == ST_INACTIVO) {
                $row->estado = '<div class="ui label mini red" > Inactivo</div > ';
                $tool .= ' 
                            <div class="ui divider"></div> 
                            <div class="item ajxUp" data-idMasc="' . $row->mascota_id . '"> 
                            <i class="green lock open icon"></i> 
                            Activar 
                            </div>';
            }
            $tool .= ' 
                        <div class="ui divider"></div> 
                        <div class="item ajxDelete" data-idMasc="' . $row->mascota_id . '"> 
                        <i class="black trash alternate icon"></i> 
                        Eliminar 
                        </div>';
            $tool .= ' 
                        </div > 
                        </div >';

            $row->tool = $tool;
        }
        return response()->json(['status' => STATUS_OK, 'data' => ['data' => $rows, 'count' => $countRegs]]);
    }

    public function edit($idMascota = '')
    {
        $clientes = Cliente::getListClientesActive();
        $especies = Especie::getAllList();
        $sexos = Sexo::getListAll();
        if ($idMascota == '') {
            return view('mascota.mascota', [
                'clientes' => $clientes,
                'especies' => $especies,
                'sexos' => $sexos,
            ]);
        }

        $rsMascota = Mascota::getMascota($idMascota);
        if (!$rsMascota) {
            return redirect()->action('MascotaController@index');
        }
        if ($rsMascota->mascota_nacimiento != '')
            $rsMascota->mascota_nacimiento = (new Carbon($rsMascota->mascota_nacimiento))->format('d/m/Y');
        return view('mascota.mascota', [
            'rsMascota' => $rsMascota,
            'clientes' => $clientes,
            'especies' => $especies,
            'sexos' => $sexos,
        ]);
    }

    public function save(Request $request)
    {
        $error = [];
        $validator = Validator::make($request->all(), [
            'mascota_nombre' => 'required',
            'mascota_sexo' => 'required',
            'mascota_especie' => 'required',
            'mascota_raza' => 'required',
            'mascota_cliente_id' => 'required',
            'mascota_nacimiento' => 'required',
        ]);
        foreach ($validator->errors()->getMessages() as $key => $message) {
            $error[$key] = $message[0];
        }

        if (count($error) > 0) {
            $res = ['status' => STATUS_FAIL, 'data' => $error, 'msg' => 'Complete los campos marcado en rojo'];
            return response()->json($res);
        }

        if ($request->filled('mascota_nacimiento')) {
            $parte = explode('/', $request->input('mascota_nacimiento'));
            $request->merge(['mascota_nacimiento' => (new Carbon($parte[2] . '-' . $parte[1] . '-' . $parte[0]))->format('Y/m/d') ]);
        }

        if (!$request->filled('mascota_id')) {
            $request->merge(['mascota_estado' => ST_NUEVO]);
            $request->merge(['mascota_fecha_registro' => Carbon::now() ]);
            $mascota = Mascota::create($request->all());
            return response()->json(['status' => STATUS_OK, 'id' => $mascota->mascota_id]);
        }
        $mascota = Mascota::updateRow($request);
        return response()->json(['status' => STATUS_OK, 'id' => $mascota->mascota_id]);
    }

    public function bloquear(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $request->merge(['mascota_id' => $request->input('id')]);
        $request->merge(['mascota_estado' => ST_INACTIVO]);
        Mascota::updateRow($request);
        return response()->json(['status' => STATUS_OK]);
    }

    public function activar(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $request->merge(['mascota_id' => $request->input('id')]);
        $request->merge(['mascota_estado' => ST_ACTIVO]);
        Mascota::updateRow($request);
        return response()->json(['status' => STATUS_OK]);
    }

    public function eliminar(Request $request)
    {
        if (!$request->filled('id')) {
            return response()->json(['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada']);
        }
        $request->merge(['mascota_id' => $request->input('id')]);
        $request->merge(['mascota_estado' => ST_ELIMINADO]);
        Mascota::updateRow($request);
        return response()->json(['status' => STATUS_OK]);
    }

    public function cargarRaza(Request $request)
    {
        $idMascota = $request->input('idMascota');
        $idEspecie = $request->input('idEspecie');
        $accion = $request->input('accion');

        if ($idEspecie == '' or $accion == '') {
            $res = ['status' => STATUS_FAIL, 'msg' => 'Error datos de entrada'];
            return response()->json($res);
        }
        $rsMascota = false;
        if ($idMascota != '') {
            $rsMascota = Mascota::getMascota($idMascota);
        }
        $idRaza = ($rsMascota) ? $rsMascota->mascota_raza : '';

        $html = '<option value="">Selecciona Raza</option>';
        $Razas = Raza::getListRazaXEspecie($idEspecie);
        if ($Razas) {
            foreach ($Razas as $var) {
                $html .= '<option ' . (($idRaza == $var->raza_id and $accion == 2) ? 'selected' : '') . ' value="' . $var->raza_id . '">' . $var->raza_nombre . '</option>';
            }
        }

        return response()->json(['status' => STATUS_OK, 'raza' => $html]);
    }

    public function historia($idMascota = '')
    {
        if ($idMascota == '')
            return redirect()->action('MascotaController@index');

        $mascotas = Mascota::getListAll();
        if (!$mascotas) {
            return redirect()->action('MascotaController@index');
        }

        $rsHistoria = Historia::getHistoriaPet($idMascota);
        if(!$rsHistoria){
            return view('historia.historia', [
                'editar' => true,
                'mascotas' => $mascotas,
                'idMascota' => $idMascota,
            ]);
        }

        $fechaReg = (new Carbon($rsHistoria->historia_fecha_registro))->format('Y/m/d');
        $hoy = (new Carbon())->format('Y/m/d');
//        $hoy = new \DateTime();
//        var_dump($fechaReg);
//        dd($hoy);

        if($hoy > $fechaReg){
            $rsMascota = Mascota::getMascota($idMascota);
            $rsCliente = Cliente::getCliente($rsMascota->mascota_cliente_id);
            $rsEspecie = Especie::getEspecie($rsMascota->mascota_especie);
            $rsRaza = Raza::getRaza($rsMascota->mascota_raza);
            $rsSexo = Sexo::getSexo($rsMascota->mascota_sexo);
            $rsUser = Usuario::getUSer($rsHistoria->historia_Usuario);


            $html['Mascota: '] = $rsMascota->mascota_nombre;
            $html['Nro Chip: '] = $rsMascota->mascota_chip;
            $html['Dueño: '] = $rsCliente->cliente_fullname;
            if ($rsMascota->mascota_nacimiento != '')
                $html['Fec. Nacimiento: '] = (new Carbon($rsMascota->mascota_nacimiento))->format('d/m/Y');
            $html['Especie: '] = $rsEspecie->especie_nombre;
            $html['Raza: '] = $rsRaza->raza_nombre;
            $html['Sexo: '] = $rsSexo->sexo_nombre;
            $html['Alergias: '] = $rsHistoria->historia_alergias;
            $html[''] = '';
            $html['Registrado por : '] = $rsUser->nombre.' '.$rsUser->apellido;
            $html['Fecha Reg.: '] = (new Carbon($rsHistoria->historia_fecha_registro))->format('d/m/Y');

            return view('historia.historia', [
                'editar' => false,
                'html' => $html,
                'rsHistoria' => $rsHistoria,
            ]);
        }
        return view('historia.historia', [
            'editar' => true,
            'mascotas' => $mascotas,
            'idMascota' => $idMascota,
            'rsHistoria' => $rsHistoria,
        ]);
    }

}