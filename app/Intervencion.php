<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Intervencion extends Model
{
    protected $table = 'tbl_intervencion';
    protected $primaryKey = 'intervencion_id';
    public $timestamps = false;
    protected $fillable = [
        'intervencion_id',
        'intervencion_interventip_id',
        'intervencion_historia_id',
        'intervencion_fecha',
        'intervencion_descripcion',
        'intervencion_resultados',
        'intervencion_estado',
        'intervencion_usuario',
    ];

    public static function getList($take,$skip,$idHistoria)
    {
        return self::getClone($idHistoria)->orderBy('intervencion_id', 'desc')
            ->limit($take)
            ->offset($skip)
            ->get();
    }

    public static function getCountIntervencion($idHistoria)
    {
        return self::getClone($idHistoria)->count();
    }

    public static function getIntervencion($idIntervencion)
    {
        return Intervencion::where('intervencion_id', '=', $idIntervencion)
            ->first();
    }

    private static function getClone($idHistoria)
    {
        return Intervencion::where('intervencion_estado', '=', ST_ACTIVO)
            ->leftJoin('tbl_tipo_intervencion', 'tbl_intervencion.intervencion_interventip_id', '=', 'tbl_tipo_intervencion.intervenciont_id')
            ->where('intervencion_historia_id', '=', $idHistoria);
    }

    /**
     * @param $request : its a request
     * @return Intervencion
     */
    public static function updateRow($request)
    {
        $intervencion = Intervencion::findOrFail($request->input('intervencion_id'));
        $intervencion->fill($request->all())->save();
        return $intervencion;
    }
}
