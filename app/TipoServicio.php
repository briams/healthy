<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoServicio extends Model
{
    protected $table = 'tbl_servicio_tipo';
    protected $primaryKey = 'servtip_id';
    public $timestamps = false;
    protected $fillable = [
        'servtip_id',
        'servtip_nombre',
        'servtip_descripcion',
    ];

    public static function getList($take,$skip)
    {
        return TipoServicio::orderBy('servtip_id', 'asc')
            ->limit($take)
            ->offset($skip)
            ->get();
    }

    public static function getCountTipoServicio()
    {
        return TipoServicio::count();
    }

    public static function getAllList()
    {
        return TipoServicio::orderBy('servtip_id', 'asc')
            ->get();
    }

    public static function getTipoServicio($idTipoServicio)
    {
        return TipoServicio::where('servtip_id', '=', $idTipoServicio)
            ->first();
    }

    /**
     * @param $request : its a request
     * @return TipoServicio
     */
    public static function updateRow($request)
    {
        $tipoServicio = TipoServicio::findOrFail($request->input('servtip_id'));
        $tipoServicio->fill($request->all())->save();
        return $tipoServicio;
    }

    public static function deleteTipoServicio($idTipoServicio)
    {
        TipoServicio::where('servtip_id', $idTipoServicio)
            ->delete();
    }
}
