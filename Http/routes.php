<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


  //Route::get('login', 'LoginController@index');
Route::post('/api/login', 'LoginController@index');
Route::post('/api/register', 'LoginController@register');
Route::post('/api/logout', 'LoginController@logout');
Route::post('/api/getuser', 'LoginController@getuser');
Route::post('/api/restore', 'LoginController@restore');
Route::post('/api/updateuser', 'LoginController@updateuser');

Route::post('/api/savefuel', 'FuelController@savefuel');
Route::post('/api/getfuels', 'FuelController@getfuels');
Route::get('/api/delfuel/{id}', 'FuelController@delfuel');
Route::get('/api/archivefuel/{id}', 'FuelController@archivefuel');
Route::get('/api/restorefuel/{id}', 'FuelController@restorefuel');
Route::get('/api/getfuel/{id}', 'FuelController@getfuel');
Route::get('/api/getmileage', 'FuelController@getmileage');

Route::get('/api/history', 'HistoryController@history');
Route::get('/api/item/{id}', 'HistoryController@item');
Route::get('/api/itemedit/{id}', 'HistoryController@item');
Route::get('/api/itemedit/{id}/{logid}', 'HistoryController@itemedit');
Route::post('/api/caresave', 'HistoryController@caresave');
Route::get('/api/delitem/{id}', 'HistoryController@delitem');





Route::get('/', 'WelcomeController@index');
Route::get('/logout', function(){
    return redirect('/');
});
Route::get('/active/{hash}/{id}', 'LoginController@confirmeEmail');
Route::get('/{any}', 'WelcomeController@index')->where('any', '.*');

//Route::controllers([
//	'auth' => 'Auth\AuthController',
//	'password' => 'Auth\PasswordController',
//]);
