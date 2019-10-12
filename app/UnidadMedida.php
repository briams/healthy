<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnidadMedida extends Model
{
    protected $table = 'tbl_unidad_medida';
    protected $primaryKey = 'umd_id';
    public $timestamps = false;
    protected $fillable = [
        'umd_id',
        'umd_codigo',
        'umd_descripcion',
        'umd_orden',
    ];

    public static function getList($take,$skip)
    {
        return UnidadMedida::orderBy('umd_orden', 'asc')
            ->limit($take)
            ->offset($skip)
            ->get();
    }

    public static function getCountUnidMedida()
    {
        return UnidadMedida::count();
    }

    public static function getAllList()
    {
        return UnidadMedida::orderBy('umd_orden', 'asc')
            ->get();
    }

    public static function getUnidMedida($idUnidMedida)
    {
        return UnidadMedida::where('umd_id', '=', $idUnidMedida)
            ->first();
    }

    /**
     * @param $request : its a request
     * @return UnidadMedida
     */
    public static function updateRow($request)
    {
        $unidMedida = UnidadMedida::findOrFail($request->input('umd_id'));
        $unidMedida->fill($request->all())->save();
        return $unidMedida;
    }

    public static function deleteUnidMedida($idUnidMedida)
    {
        UnidadMedida::where('umd_id', $idUnidMedida)
            ->delete();
    }
}
