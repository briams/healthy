<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    protected $table = 'tbl_examen';
    protected $primaryKey = 'examen_id';
    public $timestamps = false;
    protected $fillable = [
        'examen_id',
        'examen_consulta_id',
        'examen_exament_id',
        'examen_observaciones',
        'examen_resultados',
    ];

    public static function getList($take,$skip,$idConsulta)
    {
        return self::getClone($idConsulta)->orderBy('examen_id', 'desc')
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
        return self::getClone($idConsulta)->orderBy('examen_id', 'desc')
            ->get();
    }

    private static function getClone($idConsulta)
    {
        return Examen::where('examen_consulta_id', '=', $idConsulta);
    }

    /**
     * @param $request : its a request
     * @return Examen
     */
    public static function updateRow($request)
    {
        $examen = Examen::findOrFail($request->input('examen_id'));
        $examen->fill($request->all())->save();
        return $examen;
    }

    public static function deleteExamen($idExamen)
    {
        Examen::where('examen_id', $idExamen)
            ->delete();
    }

    public static function deleteAllExamen($idConsulta)
    {
        Examen::where('examen_consulta_id', $idConsulta)
            ->delete();
    }
}
