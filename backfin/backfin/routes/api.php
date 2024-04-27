<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\SiteGSMController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('inscription', 'App\Http\Controllers\UtilisateurController@inscription');
Route::post('connexion', 'App\Http\Controllers\UtilisateurController@connexion');
Route::post('storesite', 'App\Http\Controllers\SiteGSMController@storesite');
Route::get('showsite','App\Http\Controllers\SiteGSMController@show');
Route::get('showsiteid/{id}','App\Http\Controllers\SiteGSMController@showid');
Route::delete('deletesite/{id}', 'App\Http\Controllers\SiteGSMController@destroysite');
Route::put('updatesite/{id}', 'App\Http\Controllers\SiteGSMController@updatesite');
