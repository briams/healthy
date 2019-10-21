<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoExamen extends Model
{
    protected $table = 'tbl_tipo_examen';
    protected $primaryKey = 'exament_id';
    public $timestamps = false;
    protected $fillable = [
        'exament_id',
        'exament_nombre',
        'exament_descripcion',
    ];

    public static function getList($take,$skip)
    {
        return TipoExamen::orderBy('exament_id', 'asc')
            ->limit($take)
            ->offset($skip)
            ->get();
    }

    public static function getCountTipoExamen()
    {
        return TipoExamen::count();
    }

    public static function getAllList()
    {
        return TipoExamen::orderBy('exament_id', 'asc')
            ->get();
    }

    public static function getTipoExamen($idTipoExamen)
    {
        return TipoExamen::where('exament_id', '=', $idTipoExamen)
            ->first();
    }

    /**
     * @param $request : its a request
     * @return TipoExamen
     */
    public static function updateRow($request)
    {
        $tipoExamen = TipoExamen::findOrFail($request->input('exament_id'));
        $tipoExamen->fill($request->all())->save();
        return $tipoExamen;
    }

    public static function deleteTipoExamen($idTipoExamen)
    {
        TipoExamen::where('exament_id', $idTipoExamen)
            ->delete();
    }
}
