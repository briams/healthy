<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoIntervencion extends Model
{
    protected $table = 'tbl_tipo_intervencion';
    protected $primaryKey = 'intervenciont_id';
    public $timestamps = false;
    protected $fillable = [
        'intervenciont_id',
        'intervenciont_nombre',
        'intervenciont_descripcion',
    ];

    public static function getList($take,$skip)
    {
        return TipoIntervencion::orderBy('intervenciont_id', 'asc')
            ->limit($take)
            ->offset($skip)
            ->get();
    }

    public static function getCountTipoIntervencion()
    {
        return TipoIntervencion::count();
    }

    public static function getAllList()
    {
        return TipoIntervencion::orderBy('intervenciont_id', 'asc')
            ->get();
    }

    public static function getTipoIntervencion($idTipoIntervencion)
    {
        return TipoIntervencion::where('intervenciont_id', '=', $idTipoIntervencion)
            ->first();
    }

    /**
     * @param $request : its a request
     * @return TipoIntervencion
     */
    public static function updateRow($request)
    {
        $tipoIntervencion = TipoIntervencion::findOrFail($request->input('intervenciont_id'));
        $tipoIntervencion->fill($request->all())->save();
        return $tipoIntervencion;
    }

    public static function deleteTipoIntervencion($idTipoIntervencion)
    {
        TipoIntervencion::where('intervenciont_id', $idTipoIntervencion)
            ->delete();
    }
}
