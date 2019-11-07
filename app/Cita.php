<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $table = 'tbl_cita';
    protected $primaryKey = 'cita_id';
    public $timestamps = false;
    protected $fillable = [
        'cita_id',
        'cita_cliente_id',
        'cita_mascota_id',
        'cita_fecha',
        'cita_motivo',
        'cita_fecha_registro',
        'cita_estado',
    ];

    public static function getList($take, $skip, $inicio, $fin)
    {
        return self::getClone($inicio, $fin)->orderBy('cita_fecha', 'asc')
            ->limit($take)
            ->offset($skip)
            ->get();
    }

    public static function getCountCita($inicio, $fin)
    {
        return self::getClone($inicio, $fin)->count();
    }

    public static function getListAll($inicio, $fin)
    {
        return self::getClone($inicio, $fin)->orderBy('cita_fecha', 'asc')
            ->get();
    }

    public static function getCita($idCita)
    {
        return Cita::where('cita_id', '=', $idCita)
            ->first();
    }

    private static function getClone($inicio, $fin)
    {
        return Cita::leftJoin('tbl_mascota', 'tbl_cita.cita_mascota_id', '=', 'tbl_mascota.mascota_id')
            ->leftJoin('tbl_cliente', 'tbl_cita.cita_cliente_id', '=', 'tbl_cliente.cliente_id')
            ->where('cita_estado', '!=', ST_ELIMINADO)
            ->whereDate('cita_fecha', '>=', $inicio)
            ->whereDate('cita_fecha', '<=', $fin);
    }

    /**
     * @param $request : its a request
     * @return Cita
     */
    public static function updateRow($request)
    {
        $cita = Cita::findOrFail($request->input('cita_id'));
        $cita->fill($request->all())->save();
        return $cita;
    }
}
