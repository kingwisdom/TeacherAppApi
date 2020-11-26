<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('articles', 'ArticleController@index');
Route::get('articles/{article}', 'ArticleController@show');
Route::post('articles', 'ArticleController@store');
Route::put('articles/{article}', 'ArticleController@update');
Route::delete('articles/{article}', 'ArticleController@delete');


Route::group([
    'middleware'=>'api',
    'namespace' =>'App\Http\Controllers',
    'prefix'=>'auth'
], function($router){
    Route::post('/login','AuthController@login');
    Route::post('register','AuthController@register');
    Route::post('logout','AuthController@logout');
    Route::get('/profile','AuthController@profile');
    Route::post('refresh','AuthController@refresh');
});

Route::group([
    'middleware'=>'api',
    'namespace' =>'App\Http\Controllers',
], function($router){
    Route::resource('article','ArticleController');
});
