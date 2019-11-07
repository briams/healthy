<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', 'MainController@index');
Route::post('/login', 'MainController@login')->name('login');
Route::post('/logout', 'MainController@logout')->name('logout');

Route::group(['prefix'=>'module', 'middleware' => ['validateSession','validatePrivilegues']],function(){
    Route::get('/', 'ModuleController@index');
    Route::post('/get-main-list', 'ModuleController@GetMainList');
    Route::get('/editar/{idModulo?}','ModuleController@edit');
    Route::post('/save','ModuleController@save');
    Route::post('/bloquear','ModuleController@bloquear');
    Route::post('/activar','ModuleController@activar');
});

Route::group(['prefix'=>'perfil', 'middleware' => ['validateSession','validatePrivilegues']],function(){
    Route::get('/', 'PerfilController@index');
    Route::post('/get-main-list', 'PerfilController@GetMainList');
    Route::get('/editar/{idPerfil?}','PerfilController@edit');
    Route::post('/save','PerfilController@save');
    Route::post('/bloquear','PerfilController@bloquear');
    Route::post('/activar','PerfilController@activar');
    Route::post('/eliminar','PerfilController@eliminar');
});

Route::group(['prefix'=>'cargo', 'middleware' => ['validateSession','validatePrivilegues']],function(){
    Route::get('/', 'CargoController@index');
    Route::post('/get-main-list', 'CargoController@GetMainList');
    Route::get('/editar/{idCargo?}','CargoController@edit');
    Route::post('/save','CargoController@save');
    Route::post('/eliminar','CargoController@eliminar');
});

Route::group(['prefix'=>'personal', 'middleware' => ['validateSession','validatePrivilegues']],function(){
    Route::get('/', 'PersonalController@index');
    Route::post('/get-main-list', 'PersonalController@GetMainList');
    Route::get('/editar/{idPersonal?}','PersonalController@edit');
    Route::post('/save','PersonalController@save');
    Route::post('/bloquear','PersonalController@bloquear');
    Route::post('/activar','PersonalController@activar');
    Route::post('/eliminar','PersonalController@eliminar');
    Route::post('/generate-user','PersonalController@generarUsuario');
});

Route::group(['prefix'=>'usuarios', 'middleware' => ['validateSession','validatePrivilegues']],function(){
    Route::get('/', 'UsuarioController@index');
    Route::post('/get-main-list', 'UsuarioController@GetMainList');
    Route::get('/editar/{idUser?}','UsuarioController@edit');
    Route::post('/save','UsuarioController@save');
    Route::post('/bloquear','UsuarioController@bloquear');
    Route::post('/activar','UsuarioController@activar');
    Route::post('/eliminar','UsuarioController@eliminar');
});

Route::group(['prefix'=>'especie', 'middleware' => ['validateSession','validatePrivilegues']],function(){
    Route::get('/', 'EspecieController@index');
    Route::post('/get-main-list', 'EspecieController@GetMainList');
    Route::get('/editar/{idEspecie?}','EspecieController@edit');
    Route::post('/save','EspecieController@save');
    Route::post('/eliminar','EspecieController@eliminar');
});

Route::group(['prefix'=>'raza', 'middleware' => ['validateSession','validatePrivilegues']],function(){
    Route::get('/', 'RazaController@index');
    Route::post('/get-main-list', 'RazaController@GetMainList');
    Route::get('/editar/{idRaza?}','RazaController@edit');
    Route::post('/save','RazaController@save');
    Route::post('/eliminar','RazaController@eliminar');
});

Route::group(['prefix'=>'sexo', 'middleware' => ['validateSession','validatePrivilegues']],function(){
    Route::get('/', 'SexoController@index');
    Route::post('/get-main-list', 'SexoController@GetMainList');
    Route::get('/editar/{idSexo?}','SexoController@edit');
    Route::post('/save','SexoController@save');
    Route::post('/eliminar','SexoController@eliminar');
});

Route::group(['prefix'=>'tipo-documento', 'middleware' => ['validateSession','validatePrivilegues']],function(){
    Route::get('/', 'TipoDocumentoController@index');
    Route::post('/get-main-list', 'TipoDocumentoController@GetMainList');
    Route::get('/editar/{idTipDoc?}','TipoDocumentoController@edit');
    Route::post('/save','TipoDocumentoController@save');
    Route::post('/eliminar','TipoDocumentoController@eliminar');
});

Route::group(['prefix'=>'unidad-medida', 'middleware' => ['validateSession','validatePrivilegues']],function(){
    Route::get('/', 'UnidadMedidaController@index');
    Route::post('/get-main-list', 'UnidadMedidaController@GetMainList');
    Route::get('/editar/{idUnidMedida?}','UnidadMedidaController@edit');
    Route::post('/save','UnidadMedidaController@save');
    Route::post('/eliminar','UnidadMedidaController@eliminar');
});

Route::group(['prefix'=>'tipo-servicio', 'middleware' => ['validateSession','validatePrivilegues']],function(){
    Route::get('/', 'TipoServicioController@index');
    Route::post('/get-main-list', 'TipoServicioController@GetMainList');
    Route::get('/editar/{idTipoServicio?}','TipoServicioController@edit');
    Route::post('/save','TipoServicioController@save');
    Route::post('/eliminar','TipoServicioController@eliminar');
});

Route::group(['prefix'=>'tipo-intervencion', 'middleware' => ['validateSession','validatePrivilegues']],function(){
    Route::get('/', 'TipoIntervencionController@index');
    Route::post('/get-main-list', 'TipoIntervencionController@GetMainList');
    Route::get('/editar/{idTipoIntervencion?}','TipoIntervencionController@edit');
    Route::post('/save','TipoIntervencionController@save');
    Route::post('/eliminar','TipoIntervencionController@eliminar');
});

Route::group(['prefix'=>'tipo-examen', 'middleware' => ['validateSession','validatePrivilegues']],function(){
    Route::get('/', 'TipoExamenController@index');
    Route::post('/get-main-list', 'TipoExamenController@GetMainList');
    Route::get('/editar/{idTipoExamen?}','TipoExamenController@edit');
    Route::post('/save','TipoExamenController@save');
    Route::post('/eliminar','TipoExamenController@eliminar');
});

Route::group(['prefix'=>'vacuna', 'middleware' => ['validateSession','validatePrivilegues']],function(){
    Route::get('/', 'VacunaController@index');
    Route::post('/get-main-list', 'VacunaController@GetMainList');
    Route::get('/editar/{idVacuna?}','VacunaController@edit');
    Route::post('/save','VacunaController@save');
    Route::post('/bloquear','VacunaController@bloquear');
    Route::post('/activar','VacunaController@activar');
    Route::post('/eliminar','VacunaController@eliminar');
});

Route::group(['prefix'=>'producto', 'middleware' => ['validateSession','validatePrivilegues']],function(){
    Route::get('/', 'ProductoController@index');
    Route::post('/get-main-list', 'ProductoController@GetMainList');
    Route::get('/editar/{idProducto?}','ProductoController@edit');
    Route::post('/save','ProductoController@save');
    Route::post('/bloquear','ProductoController@bloquear');
    Route::post('/activar','ProductoController@activar');
    Route::post('/eliminar','ProductoController@eliminar');
});

Route::group(['prefix'=>'cliente', 'middleware' => ['validateSession','validatePrivilegues']],function(){
    Route::get('/', 'ClienteController@index');
    Route::post('/get-main-list', 'ClienteController@GetMainList');
    Route::get('/editar/{idCliente?}','ClienteController@edit');
    Route::post('/save','ClienteController@save');
    Route::post('/bloquear','ClienteController@bloquear');
    Route::post('/activar','ClienteController@activar');
    Route::post('/eliminar','ClienteController@eliminar');
    Route::post('/buscar-documento','ClienteController@searchDoc');
});

Route::group(['prefix'=>'mascota', 'middleware' => ['validateSession','validatePrivilegues']],function(){
    Route::get('/', 'MascotaController@index');
    Route::post('/get-main-list', 'MascotaController@GetMainList');
    Route::get('/editar/{idMascota?}','MascotaController@edit');
    Route::post('/save','MascotaController@save');
    Route::post('/bloquear','MascotaController@bloquear');
    Route::post('/activar','MascotaController@activar');
    Route::post('/eliminar','MascotaController@eliminar');
    Route::post('/cargar-raza','MascotaController@cargarRaza');
    Route::get('/historia/{idMascota?}','MascotaController@historia');
});

Route::group(['prefix'=>'historia', 'middleware' => ['validateSession','validatePrivilegues']],function(){
    Route::post('/save','HistoriaController@save');
});

Route::group(['prefix'=>'vacunacion', 'middleware' => ['validateSession','validatePrivilegues']],function(){
    Route::post('/get-main-list', 'VacunacionController@GetMainList');
    Route::get('/editar/{idHistoria}/{idVacunacion?}','VacunacionController@edit');
    Route::post('/save','VacunacionController@save');
    Route::post('/eliminar','VacunacionController@eliminar');
});

Route::group(['prefix'=>'internamiento', 'middleware' => ['validateSession','validatePrivilegues']],function(){
    Route::post('/get-main-list', 'InternamientoController@GetMainList');
    Route::get('/editar/{idHistoria}/{idInternamiento?}','InternamientoController@edit');
    Route::post('/save','InternamientoController@save');
    Route::post('/eliminar','InternamientoController@eliminar');
});

Route::group(['prefix'=>'tratamiento', 'middleware' => ['validateSession','validatePrivilegues']],function(){
    Route::post('/get-main-list', 'TratamientoController@GetMainList');
    Route::get('/editar/{idHistoria}/{idTratamiento?}','TratamientoController@edit');
    Route::post('/save','TratamientoController@save');
    Route::post('/eliminar','TratamientoController@eliminar');
    Route::post('/get-main-list-detalle', 'TratamientoController@GetMainListDetalle');
    Route::post('/add-detalle','TratamientoController@addDetalle');
    Route::post('/remove-detalle','TratamientoController@removeDetalle');
});

Route::group(['prefix'=>'servicio', 'middleware' => ['validateSession','validatePrivilegues']],function(){
    Route::post('/get-main-list', 'ServicioController@GetMainList');
    Route::get('/editar/{idHistoria}/{idServicio?}','ServicioController@edit');
    Route::post('/save','ServicioController@save');
    Route::post('/eliminar','ServicioController@eliminar');
});

Route::group(['prefix'=>'intervencion', 'middleware' => ['validateSession','validatePrivilegues']],function(){
    Route::post('/get-main-list', 'IntervencionController@GetMainList');
    Route::get('/editar/{idHistoria}/{idServicio?}','IntervencionController@edit');
    Route::post('/save','IntervencionController@save');
    Route::post('/eliminar','IntervencionController@eliminar');
});

Route::group(['prefix'=>'consulta', 'middleware' => ['validateSession','validatePrivilegues']],function(){
    Route::post('/get-main-list', 'ConsultaController@GetMainList');
    Route::get('/editar/{idHistoria}/{idConsulta?}','ConsultaController@edit');
    Route::post('/save','ConsultaController@save');
    Route::post('/eliminar','ConsultaController@eliminar');
    Route::post('/get-main-list-detalle', 'ConsultaController@GetMainListDetalle');
    Route::post('/add-detalle','ConsultaController@addDetalle');
    Route::post('/remove-detalle','ConsultaController@removeDetalle');
    Route::post('/get-main-list-examen', 'ConsultaController@GetMainListExamen');
    Route::post('/add-examen','ConsultaController@addExamen');
    Route::post('/remove-examen','ConsultaController@removeExamen');
});

Route::group(['prefix'=>'cita', 'middleware' => ['validateSession','validatePrivilegues']],function(){
    Route::get('/', 'CitaController@index');
    Route::post('/get-main-list', 'CitaController@GetMainList');
    Route::get('/editar/{idCita?}','CitaController@edit');
    Route::post('/save','CitaController@save');
    Route::post('/bloquear','CitaController@bloquear');
    Route::post('/activar','CitaController@activar');
    Route::post('/eliminar','CitaController@eliminar');
    Route::post('/cargar-mascota','CitaController@cargarMascota');
    Route::get('/down-excel/{diai?}/{mesi?}/{anioi?}/{diaf?}/{mesf?}/{aniof?}', 'CitaController@downExcel');
});

Route::group(['prefix'=>'reporte-producto', 'middleware' => ['validateSession','validatePrivilegues']],function(){
    Route::get('/', 'ReporteController@index');
    Route::post('/get-main-list', 'ReporteController@GetMainList');
});

Route::group(['prefix'=>'reporte-service', 'middleware' => ['validateSession','validatePrivilegues']],function(){
    Route::get('/', 'ReportBestServiceController@index');
    Route::post('/get-main-list', 'ReportBestServiceController@GetMainList');
});

Route::group(['prefix'=>'reporte-cliente', 'middleware' => ['validateSession','validatePrivilegues']],function(){
    Route::get('/', 'ReportClienteController@index');
    Route::post('/get-main-list', 'ReportClienteController@GetMainList');
});