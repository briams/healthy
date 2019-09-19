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

// Route::get('/clientes', 'ClienteController@getAllClients');

Route::group(['prefix'=>'usuarios'],function(){
  Route::get('/', 'UsuarioController@index');
});
