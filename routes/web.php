<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'UserController@index');
Route::get('/ssm', 'UserController@ssm');

Route::post('/user' , 'UserController@store');
Route::post('/update-point' , 'UserController@updatePoint');
Route::post('/delete-user' , 'UserController@deleteUser');
Route::post('/get-api' , 'UserController@getAPI');