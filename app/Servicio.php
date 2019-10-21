<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table = 'tbl_servicio';
    protected $primaryKey = 'servicio_id';
    public $timestamps = false;
    protected $fillable = [
        'servicio_id',
        'servicio_servtip_id',
        'servicio_historia_id',
        'servicio_fecha',
        'servicio_observacion',
        'servicio_estado',
    ];

    public static function getList($take,$skip,$idHistoria)
    {
        return self::getClone($idHistoria)->orderBy('servicio_id', 'desc')
            ->limit($take)
            ->offset($skip)
            ->get();
    }

    public static function getCountServicio($idHistoria)
    {
        return self::getClone($idHistoria)->count();
    }

    public static function getServicio($idServicio)
    {
        return Servicio::where('servicio_id', '=', $idServicio)
            ->first();
    }

    private static function getClone($idHistoria)
    {
        return Servicio::where('servicio_estado', '=', ST_ACTIVO)
            ->leftJoin('tbl_servicio_tipo', 'tbl_servicio.servicio_servtip_id', '=', 'tbl_servicio_tipo.servtip_id')
            ->where('servicio_historia_id', '=', $idHistoria);
    }

    /**
     * @param $request : its a request
     * @return Servicio
     */
    public static function updateRow($request)
    {
        $servicio = Servicio::findOrFail($request->input('servicio_id'));
        $servicio->fill($request->all())->save();
        return $servicio;
    }
}
