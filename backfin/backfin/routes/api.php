<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\SiteGSMController;
use App\Http\Controllers\ParamArchiveController;
use App\Http\Controllers\docFinanceController;
use App\Http\Controllers\cellule2GController;
use App\Http\Controllers\cellule3GController;
use App\Http\Controllers\cellule4GController;

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



/////////////les api de TB SITE 
Route::get('/sites/by-region-and-delegotion', 'App\Http\Controllers\SiteGSMController@getSitesByRegionAndDelegotion');
Route::get('getdoc/{codesite}', 'App\Http\Controllers\SiteGSMController@getDocFinByCodeSite');
Route::get('getarch/{codesite}', 'App\Http\Controllers\SiteGSMController@getArchiveByCodeSite');
Route::get('/sitestbF/by-region-and-delegotion-and-fournisseur', 'App\Http\Controllers\SiteGSMController@getsitesByRegionAndDelegotionAndFournisseur');

Route::get('/sitestb/by-region-and-delegotion', 'App\Http\Controllers\SiteGSMController@getfournisseurByRegionAndDelegotion');
Route::get('/sites/by-region', 'App\Http\Controllers\SiteGSMController@getSitesByRegion');
Route::post('adddoc', 'App\Http\Controllers\ParamArchiveController@store');
// Route for associating a ParamArchive with a SiteGSM
Route::post('sites/{codesite}', 'App\Http\Controllers\SiteGSMController@associateArchive');
Route::post('docfin/{codesite}', 'App\Http\Controllers\SiteGSMController@associateDocFinanciere');
Route::put('docfin2/{iddocfin}', 'App\Http\Controllers\docFinanceController@updateDocFinanciere');
Route::post('docfin3/{iddocfin}', 'App\Http\Controllers\docFinanceController@uploadContract');

Route::get('getiddoc/{codesite}', 'App\Http\Controllers\SiteGSMController@getIdDocFinByCodeSite');
Route::post('sitesar/{codesite}', 'App\Http\Controllers\SiteGSMController@associateparamarchive2');


Route::put('users/{id}', 'App\Http\Controllers\UtilisateurController@edition');
Route::get('utilisateurs/{id}', 'App\Http\Controllers\UtilisateurController@getImage');
Route::post('inscription', 'App\Http\Controllers\UtilisateurController@inscription');
Route::post('addAccount', 'App\Http\Controllers\UtilisateurController@addAccount');
Route::post('connexion', 'App\Http\Controllers\UtilisateurController@connexion');

Route::post('storesite', 'App\Http\Controllers\SiteGSMController@storesite');
Route::get('showsite','App\Http\Controllers\SiteGSMController@show');
Route::get('showsiteid/{id}','App\Http\Controllers\SiteGSMController@showid');
Route::delete('deletesite/{id}', 'App\Http\Controllers\SiteGSMController@destroysite');
Route::put('updatesite/{id}', 'App\Http\Controllers\SiteGSMController@updatesite');
Route::get('getsiteid/{codesite}','App\Http\Controllers\SiteGSMController@getIdSiteByCodeSite');




//////////////////////////clellule2G api cellule2GController
Route::post('cel2Gadd/{idSite}', 'App\Http\Controllers\cellule2GController@store');
Route::put('cel2Gupdate/{idCel}', 'App\Http\Controllers\cellule2GController@update');
Route::get('getidCel2G/{codeCellule}','App\Http\Controllers\cellule2GController@getidCelBycodeCellule');
Route::get('showidCel2G/{idCel}','App\Http\Controllers\cellule2GController@showid');

//////////////////////////clellule3G api cellule3GController
Route::post('cel3Gadd/{idSite}', 'App\Http\Controllers\cellule3GController@store');
Route::put('cel3Gupdate/{idcel}', 'App\Http\Controllers\cellule3GController@update');
Route::get('getidCel3G/{codeCellule}','App\Http\Controllers\cellule3GController@getidCelBycodeCellule');
Route::get('showidCel3G/{idcel}','App\Http\Controllers\cellule3GController@showid');

//////////////////////////clellule4G api cellule4GController
Route::post('cel4Gadd/{idSite}', 'App\Http\Controllers\cellule4GController@store');
Route::put('cel4Gupdate/{idCel}', 'App\Http\Controllers\cellule4GController@update');
Route::get('getidCel4G/{codeCellule}','App\Http\Controllers\cellule4GController@getidCelBycodeCellule');
Route::get('showidCel4G/{idCel}','App\Http\Controllers\cellule4GController@showid');


/////////////////////////////////TableBoardSITE