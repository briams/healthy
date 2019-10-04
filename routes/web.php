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
