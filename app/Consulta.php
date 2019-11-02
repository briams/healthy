<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    protected $table = 'tbl_consulta';
    protected $primaryKey = 'consulta_id';
    public $timestamps = false;
    protected $fillable = [
        'consulta_id',
        'consulta_historia_id',
        'consulta_peso',
        'consulta_mucosa',
        'consulta_temperatura',
        'consulta_frec_cardiaca',
        'consulta_frec_respiratoria',
        'consulta_observaciones',
        'consulta_fecha_registro',
        'consulta_estado',
        'consulta_usuario',
    ];

    public static function getList($take,$skip,$idHistoria)
    {
        return self::getClone($idHistoria)->orderBy('consulta_id', 'desc')
            ->limit($take)
            ->offset($skip)
            ->get();
    }

    public static function getCountConsulta($idHistoria)
    {
        return self::getClone($idHistoria)->count();
    }

    public static function getConsulta($idConsulta)
    {
        return Consulta::where('consulta_id', '=', $idConsulta)
            ->first();
    }

    private static function getClone($idHistoria)
    {
        return Consulta::where('consulta_estado', '=', ST_ACTIVO)
            ->where('consulta_historia_id', '=', $idHistoria);
    }

    /**
     * @param $request : its a request
     * @return Consulta
     */
    public static function updateRow($request)
    {
        $consulta = Consulta::findOrFail($request->input('consulta_id'));
        $consulta->fill($request->all())->save();
        return $consulta;
    }
}
