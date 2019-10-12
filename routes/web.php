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

Route::group(['prefix'=>'module'],function(){
    Route::get('/', 'ModuleController@index');
    Route::post('/get-main-list', 'ModuleController@GetMainList')->name('get-list');
    Route::get('/editar/{idModulo?}','ModuleController@edit');
    Route::post('/save','ModuleController@save');
    Route::post('/bloquear','ModuleController@bloquear');
    Route::post('/activar','ModuleController@activar');
});

Route::group(['prefix'=>'perfil'],function(){
    Route::get('/', 'PerfilController@index');
    Route::post('/get-main-list', 'PerfilController@GetMainList');
    Route::get('/editar/{idPerfil?}','PerfilController@edit');
    Route::post('/save','PerfilController@save');
    Route::post('/bloquear','PerfilController@bloquear');
    Route::post('/activar','PerfilController@activar');
    Route::post('/eliminar','PerfilController@eliminar');
});

Route::group(['prefix'=>'usuarios'],function(){
    Route::get('/', 'UsuarioController@index');
    Route::post('/get-main-list', 'UsuarioController@GetMainList');
    Route::get('/editar/{idUser?}','UsuarioController@edit');
    Route::post('/save','UsuarioController@save');
    Route::post('/bloquear','UsuarioController@bloquear');
    Route::post('/activar','UsuarioController@activar');
    Route::post('/eliminar','UsuarioController@eliminar');
});

Route::group(['prefix'=>'especie'],function(){
    Route::get('/', 'EspecieController@index');
    Route::post('/get-main-list', 'EspecieController@GetMainList');
    Route::get('/editar/{idEspecie?}','EspecieController@edit');
    Route::post('/save','EspecieController@save');
    Route::post('/eliminar','EspecieController@eliminar');
});

Route::group(['prefix'=>'raza'],function(){
    Route::get('/', 'RazaController@index');
    Route::post('/get-main-list', 'RazaController@GetMainList');
    Route::get('/editar/{idRaza?}','RazaController@edit');
    Route::post('/save','RazaController@save');
    Route::post('/eliminar','RazaController@eliminar');
});

Route::group(['prefix'=>'sexo'],function(){
    Route::get('/', 'SexoController@index');
    Route::post('/get-main-list', 'SexoController@GetMainList');
    Route::get('/editar/{idSexo?}','SexoController@edit');
    Route::post('/save','SexoController@save');
    Route::post('/eliminar','SexoController@eliminar');
});

Route::group(['prefix'=>'tipo-documento'],function(){
    Route::get('/', 'TipoDocumentoController@index');
    Route::post('/get-main-list', 'TipoDocumentoController@GetMainList');
    Route::get('/editar/{idTipDoc?}','TipoDocumentoController@edit');
    Route::post('/save','TipoDocumentoController@save');
    Route::post('/eliminar','TipoDocumentoController@eliminar');
});

Route::group(['prefix'=>'unidad-medida'],function(){
    Route::get('/', 'UnidadMedidaController@index');
    Route::post('/get-main-list', 'UnidadMedidaController@GetMainList');
    Route::get('/editar/{idUnidMedida?}','UnidadMedidaController@edit');
    Route::post('/save','UnidadMedidaController@save');
    Route::post('/eliminar','UnidadMedidaController@eliminar');
});

Route::group(['prefix'=>'vacuna'],function(){
    Route::get('/', 'VacunaController@index');
    Route::post('/get-main-list', 'VacunaController@GetMainList');
    Route::get('/editar/{idVacuna?}','VacunaController@edit');
    Route::post('/save','VacunaController@save');
    Route::post('/bloquear','VacunaController@bloquear');
    Route::post('/activar','VacunaController@activar');
    Route::post('/eliminar','VacunaController@eliminar');
});

Route::group(['prefix'=>'producto'],function(){
    Route::get('/', 'ProductoController@index');
    Route::post('/get-main-list', 'ProductoController@GetMainList');
    Route::get('/editar/{idProducto?}','ProductoController@edit');
    Route::post('/save','ProductoController@save');
    Route::post('/bloquear','ProductoController@bloquear');
    Route::post('/activar','ProductoController@activar');
    Route::post('/eliminar','ProductoController@eliminar');
});

Route::group(['prefix'=>'cliente'],function(){
    Route::get('/', 'ClienteController@index');
    Route::post('/get-main-list', 'ClienteController@GetMainList');
    Route::get('/editar/{idCliente?}','ClienteController@edit');
    Route::post('/save','ClienteController@save');
    Route::post('/bloquear','ClienteController@bloquear');
    Route::post('/activar','ClienteController@activar');
    Route::post('/eliminar','ClienteController@eliminar');
    Route::post('/buscar-documento','ClienteController@searchDoc');
});

Route::group(['prefix'=>'mascota'],function(){
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

Route::group(['prefix'=>'historia'],function(){
    Route::post('/save','HistoriaController@save');
});

Route::group(['prefix'=>'vacunacion'],function(){
    Route::post('/get-main-list', 'VacunacionController@GetMainList');
    Route::get('/editar/{idHistoria}/{idVacunacion?}','VacunacionController@edit');
    Route::post('/save','VacunacionController@save');
    Route::post('/eliminar','VacunacionController@eliminar');
});
