<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportTest extends Model
{
    protected $table = 'reporte_test';
//    protected $primaryKey = 'raza_id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'fecha',
        'strType',
        'type',
        'orden',
        'idHistoria',
        'idUsuario',
        'estado',
        'resumen',
    ];

    public static function getCountServiceFecha($take, $skip, $inicio, $fin)
    {
        return ReportTest::selectRaw('count(id) as cant , strType ')
            ->whereDate('fecha', '>=', $inicio)
            ->whereDate('fecha', '<=', $fin)
            ->where('estado', '=', ST_ACTIVO)
            ->where('orden', '!=', 3)
            ->groupBy('strType')
            ->orderBy('cant', 'desc')
            ->limit($take)
            ->offset($skip)
            ->get();
    }

    public static function CountServiceFecha($inicio, $fin)
    {
        return ReportTest::selectRaw('count(id) as cant , strType ')
            ->whereDate('fecha', '>=', $inicio)
            ->whereDate('fecha', '<=', $fin)
            ->where('estado', '=', ST_ACTIVO)
            ->where('orden', '!=', 3)
            ->groupBy('strType')
            ->get();
    }

    public static function getCountServiceFechaPersonal($take, $skip, $inicio, $fin, $personal)
    {
        return ReportTest::selectRaw('count(id) as cant , strType ')
            ->whereDate('fecha', '>=', $inicio)
            ->whereDate('fecha', '<=', $fin)
            ->where('estado', '=', ST_ACTIVO)
            ->where('idUsuario', '=', $personal)
            ->where('orden', '!=', 3)
            ->groupBy('strType')
            ->orderBy('cant', 'desc')
            ->limit($take)
            ->offset($skip)
            ->get();
    }

    public static function CountServiceFechaPersonal($inicio, $fin, $personal)
    {
        return ReportTest::selectRaw('count(id) as cant , strType ')
            ->whereDate('fecha', '>=', $inicio)
            ->whereDate('fecha', '<=', $fin)
            ->where('estado', '=', ST_ACTIVO)
            ->where('idUsuario', '=', $personal)
            ->where('orden', '!=', 3)
            ->groupBy('strType')
            ->get();
    }

    public static function getCountClienteFecha($take, $skip, $inicio, $fin)
    {
        return ReportTest::selectRaw('count(distinct(idHistoria)) as cant , date(fecha) as fechaformat ')
            ->whereDate('fecha', '>=', $inicio)
            ->whereDate('fecha', '<=', $fin)
            ->where('estado', '=', ST_ACTIVO)
            ->groupBy('fechaformat')
            ->orderBy('fechaformat', 'asc')
            ->limit($take)
            ->offset($skip)
            ->get();
    }

    public static function CountClienteFecha($inicio, $fin)
    {
        return ReportTest::selectRaw('count(distinct(idHistoria)) as cant , date(fecha) as fechaformat ')
            ->whereDate('fecha', '>=', $inicio)
            ->whereDate('fecha', '<=', $fin)
            ->where('estado', '=', ST_ACTIVO)
            ->groupBy('fechaformat')
            ->get();
    }

    public static function getHistorial($idHistoria)
    {
        return ReportTest::selectRaw(' resumen , fecha ')
            ->where('idHistoria', '=', $idHistoria)
            ->where('estado', '=', ST_ACTIVO)
            ->get();
    }

}
