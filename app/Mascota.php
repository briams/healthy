<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mascota extends Model
{
    protected $table = 'tbl_mascota';
    protected $primaryKey = 'mascota_id';
    public $timestamps = false;
    protected $fillable = [
        'mascota_id',
        'mascota_nombre',
        'mascota_sexo',
        'mascota_especie',
        'mascota_raza',
        'mascota_cliente_id',
        'mascota_peso',
        'mascota_tamano',
        'mascota_pelaje',
        'mascota_nacimiento',
        'mascota_atributo',
        'mascota_chip',
        'mascota_fecha_registro',
        'mascota_estado',
    ];

    public static function getList($take, $skip , $especie , $raza , $sexo)
    {
        return self::getClone( $especie , $raza , $sexo )
            ->orderBy('mascota_id', 'desc')
            ->limit($take)
            ->offset($skip)
            ->get();
    }

    public static function getCountMascota($especie , $raza , $sexo)
    {
        return self::getClone( $especie , $raza , $sexo )
            ->count();
    }

    public static function getListAll()
    {
        return Mascota::where('mascota_estado', '!=', ST_ELIMINADO)
            ->orderBy('mascota_id', 'desc')
            ->get();
    }

    public static function getListMascotaXCliente($idCliente)
    {
        return Mascota::where('mascota_estado', '!=', ST_ELIMINADO)
            ->where('mascota_cliente_id', '=', $idCliente)
            ->orderBy('mascota_id', 'desc')
            ->get();
    }

    public static function getMascota($idMascota)
    {
        return Mascota::where('mascota_id', '=', $idMascota)
            ->first();
    }

    private static function getClone( $especie , $raza , $sexo )
    {
        $rsMascota = Mascota::where('mascota_estado', '!=', ST_ELIMINADO)
            ->leftJoin('tbl_cliente', 'tbl_mascota.mascota_cliente_id', '=', 'tbl_cliente.cliente_id')
            ->leftJoin('tbl_especie', 'tbl_mascota.mascota_especie', '=', 'tbl_especie.especie_id')
            ->leftJoin('tbl_raza', 'tbl_mascota.mascota_raza', '=', 'tbl_raza.raza_id')
            ->leftJoin('tbl_sexo', 'tbl_mascota.mascota_sexo', '=', 'tbl_sexo.sexo_id');
        if($especie > 0){
            $rsMascota->where('mascota_especie', '=', $especie);
        }
        if($raza > 0){
            $rsMascota->where('mascota_raza', '=', $raza);
        }
        if($sexo > 0){
            $rsMascota->where('mascota_sexo', '=', $sexo);
        }

        return $rsMascota;
    }

    /**
     * @param $request : its a request
     * @return Mascota
     */
    public static function updateRow($request)
    {
        $mascota = Mascota::findOrFail($request->input('mascota_id'));
        $mascota->fill($request->all())->save();
        return $mascota;
    }
}
