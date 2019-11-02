<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vacunacion extends Model
{
    protected $table = 'tbl_vacunacion';
    protected $primaryKey = 'vacunacion_id';
    public $timestamps = false;
    protected $fillable = [
        'vacunacion_id',
        'vacunacion_vacuna_id',
        'vacunacion_historia_id',
        'vacunacion_fecha',
        'vacunacion_fecha_registro',
        'vacunacion_estado',
        'vacunacion_usuario',
    ];

    public static function getList($take,$skip,$idHistoria)
    {
        return self::getClone($idHistoria)->orderBy('vacunacion_id', 'desc')
            ->limit($take)
            ->offset($skip)
            ->get();
    }

    public static function getCountVacunacion($idHistoria)
    {
        return self::getClone($idHistoria)->count();
    }

    public static function getVacunacion($idVacunacion)
    {
        return Vacunacion::where('vacunacion_id', '=', $idVacunacion)
            ->first();
    }

    private static function getClone($idHistoria)
    {
        return Vacunacion::where('vacunacion_estado', '=', ST_ACTIVO)
            ->leftJoin('tbl_vacuna', 'tbl_vacunacion.vacunacion_vacuna_id', '=', 'tbl_vacuna.vac_id')
            ->where('vacunacion_historia_id', '=', $idHistoria);
    }

    /**
     * @param $request : its a request
     * @return Vacunacion
     */
    public static function updateRow($request)
    {
        $vacunacion = Vacunacion::findOrFail($request->input('vacunacion_id'));
        $vacunacion->fill($request->all())->save();
        return $vacunacion;
    }
}
