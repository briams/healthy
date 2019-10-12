<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Historia extends Model
{
    protected $table = 'tbl_historia';
    protected $primaryKey = 'historia_id';
    public $timestamps = false;
    protected $fillable = [
        'historia_id',
        'historia_mascota_id',
        'historia_peso',
        'historia_mucosa',
        'historia_temperatura',
        'historia_frec_cardiaca',
        'historia_frec_respiratoria',
        'historia_fecha_registro',
        'historia_alergias',
        'historia_sintomatologia',
        'historia_oservaciones',
        'historia_Usuario',
    ];

    public static function getList($take, $skip)
    {
        return self::getClone()
            ->orderBy('historia_id', 'desc')
            ->limit($take)
            ->offset($skip)
            ->get();
    }

    public static function getCountHistoria()
    {
        return self::getClone()
            ->count();
    }

    public static function getHistoria($idHistoria)
    {
        return Historia::where('historia_id', '=', $idHistoria)
            ->first();
    }

    public static function getHistoriaPet($idMascota)
    {
        return Historia::where('historia_mascota_id', '=', $idMascota)
            ->first();
    }

    private static function getClone()
    {
        return Historia::leftJoin('tbl_mascota', 'tbl_historia.historia_mascota_id', '=', 'tbl_mascota.mascota_id');
    }

    /**
     * @param $request : its a request
     * @return Historia
     */
    public static function updateRow($request)
    {
        $historia = Historia::findOrFail($request->input('historia_id'));
        $historia->fill($request->all())->save();
        return $historia;
    }
}
