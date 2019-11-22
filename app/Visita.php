<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Visita extends Model
{
    protected $table = 'tbl_visita';
    protected $primaryKey = 'vsta_id';
    public $timestamps = false;
    protected $fillable = [
        'vsta_id',
        'vsta_cliente_id',
        'vsta_fecha_llegada',
        'vsta_motivo',
        'vsta_ticket_correlativo',
        'vsta_historia_id',
        'vsta_history_found',
        'vsta_estado',
        'vsta_start_atencion',
        'vsta_registro_evento',
    ];

    public static function getList($take,$skip,$hoy)
    {
        return self::getClone($hoy)->orderBy('vsta_ticket_correlativo', 'asc')
            ->limit($take)
            ->offset($skip)
            ->get();
    }

    public static function getCountVisita($hoy)
    {
        return self::getClone($hoy)->count();
    }

    public static function getVisita($idVisita)
    {
        return Visita::where('vsta_id', '=', $idVisita)
            ->first();
    }

    public static function getVisitaxHistoria($hoy,$idHistoria)
    {
        return Visita::where('vsta_historia_id', '=', $idHistoria)
            ->whereDate('vsta_fecha_llegada', '=', $hoy)
            ->first();
    }

    public static function getLastVisita($hoy)
    {
        return self::getClone($hoy)->orderBy('vsta_ticket_correlativo', 'desc')
            ->first();
    }

    private static function getClone($hoy)
    {
        return Visita::leftJoin('tbl_cliente', 'tbl_visita.vsta_cliente_id', '=', 'tbl_cliente.cliente_id')
            ->whereDate('vsta_fecha_llegada', '=', $hoy)
            ->where('vsta_estado', '!=', ST_ELIMINADO);
    }

    /**
     * @param $request : its a request
     * @return Visita
     */
    public static function updateRow($request)
    {
        $visita = Visita::findOrFail($request->input('vsta_id'));
        $visita->fill($request->all())->save();
        return $visita;
    }

    public static function cerrarVisita($idVisita)
    {
        $data = [
            'vsta_id'  => $idVisita,
            'vsta_estado' => 4,
            'vsta_registro_evento' => Carbon::now(),
        ];
        $visita = Visita::findOrFail($idVisita);
        $visita->fill($data)->save();
        return $visita;
    }

    public static function countDuracionAtencion($fecha)
    {
        return Visita::selectRaw('TIMESTAMPDIFF(MINUTE,vsta_start_atencion,vsta_registro_evento) as minutos')
            ->whereDate('vsta_fecha_llegada', '=', $fecha)
            ->where('vsta_estado', '!=', ST_ELIMINADO)
            ->whereNotNull ('vsta_start_atencion')// inicio de atencion not null
            ->whereNotNull ('vsta_registro_evento')// registro de evento not null
            ->count();
    }

    public static function countClientesVisita($fecha)//devuelve visitas totales del dia
    {
        return Visita::whereDate('vsta_fecha_llegada', '=', $fecha)
            ->count();
    }

    public static function countHistoriasEncontradas($fecha)
    {
        return Visita::whereDate('vsta_fecha_llegada', '=', $fecha)
            ->where('vsta_estado', '!=', ST_ELIMINADO)
            ->whereNotNull ('vsta_history_found')// historias not null
            ->count();
    }

}
