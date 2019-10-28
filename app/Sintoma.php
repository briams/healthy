<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sintoma extends Model
{
    protected $table = 'tbl_sintoma';
    protected $primaryKey = 'sintoma_id';
    public $timestamps = false;
    protected $fillable = [
        'sintoma_id',
        'sintoma_consulta_id',
        'sintoma_nombre',
        'sintoma_descripcion',
    ];

    public static function getList($take,$skip,$idConsulta)
    {
        return self::getClone($idConsulta)->orderBy('sintoma_id', 'desc')
            ->limit($take)
            ->offset($skip)
            ->get();
    }

    public static function getCountConsulta($idConsulta)
    {
        return self::getClone($idConsulta)->count();
    }

    public static function getListDetalle($idConsulta)
    {
        return self::getClone($idConsulta)->orderBy('sintoma_id', 'desc')
            ->get();
    }

    private static function getClone($idConsulta)
    {
        return Sintoma::where('sintoma_consulta_id', '=', $idConsulta);
    }

    /**
     * @param $request : its a request
     * @return Sintoma
     */
    public static function updateRow($request)
    {
        $sintoma = Sintoma::findOrFail($request->input('sintoma_id'));
        $sintoma->fill($request->all())->save();
        return $sintoma;
    }

    public static function deleteSintoma($idExamen)
    {
        Sintoma::where('sintoma_id', $idExamen)
            ->delete();
    }

    public static function deleteAllSintoma($idConsulta)
    {
        Sintoma::where('sintoma_consulta_id', $idConsulta)
            ->delete();
    }
}
