<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'tbl_cliente';
    protected $primaryKey = 'cliente_id';
    public $timestamps = false;
    protected $fillable = [
        'cliente_id',
        'cliente_nombres',
        'cliente_apellidos',
        'cliente_fullname',
        'cliente_direccion',
        'cliente_telefono',
        'cliente_email',
        'cliente_tipo_documento',
        'cliente_nro_documento',
        'cliente_ubigeo',
        'cliente_sexo',
        'cliente_fecha_nacimiento',
        'cliente_registro_ts',
        'cliente_estado',
    ];

    public static function getList($take,$skip)
    {
        return self::getClone()->orderBy('cliente_id', 'desc')
            ->limit($take)
            ->offset($skip)
            ->get();
    }

    public static function getListClientesActive()
    {
        return Cliente::where('cliente_estado', '=', ST_ACTIVO)
            ->get();
    }

    public static function getCountCliente()
    {
        return self::getClone()->count();
    }

    public static function getCliente($idCliente)
    {
        return Cliente::where('cliente_id', '=', $idCliente)
            ->first();
    }

    private static function getClone()
    {
        return Cliente::where('cliente_estado', '!=', ST_ELIMINADO)
            ->leftJoin('tbl_ubigeo', 'tbl_cliente.cliente_ubigeo', '=', 'tbl_ubigeo.ubigeo')
            ->leftJoin('tbl_tipo_documento', 'tbl_cliente.cliente_tipo_documento', '=', 'tbl_tipo_documento.tdc_id');
    }

    /**
     * @param $request : its a request
     * @return Cliente
     */
    public static function updateRow($request)
    {
        $cliente = Cliente::findOrFail($request->input('cliente_id'));
        $cliente->fill($request->all())->save();
        return $cliente;
    }
}
