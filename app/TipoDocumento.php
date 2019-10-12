<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    protected $table = 'tbl_tipo_documento';
    protected $primaryKey = 'tdc_id';
    public $timestamps = false;
    protected $fillable = [
        'tdc_id',
    ];

    public static function getList($take, $skip)
    {
        return TipoDocumento::orderBy('tdc_id', 'asc')
            ->limit($take)
            ->offset($skip)
            ->get();
    }

    public static function getCountTipDoc()
    {
        return TipoDocumento::count();
    }

    public static function getListDoc()
    {
        return TipoDocumento::orderBy('tdc_id', 'asc')
            ->get();
    }

    public static function getTipDoc($idDoc)
    {
        return TipoDocumento::where('tdc_id', '=', $idDoc)
            ->first();
    }

    /**
     * @param $request : its a request
     * @return TipoDocumento
     */
    public static function updateRow($request)
    {
        $tipDoc = TipoDocumento::findOrFail($request->input('tdc_id'));
        $tipDoc->fill($request->all())->save();
        return $tipDoc;
    }

    public static function deleteTipDoc($idDoc)
    {
        TipoDocumento::where('tdc_id', $idDoc)
            ->delete();
    }
}
