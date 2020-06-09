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

Route::post('access', 'API\RegisterController@register');
Route::post('login', 'API\RegisterController@login');

Route::middleware(['auth:api', 'is_specialist'])->group(function () {
    Route::post('patient/access', 'API\RegisterController@registerPatient');
    Route::resource('analyse', 'API\AnalyseController');
    Route::resource('lesion', 'API\LesionController');
    Route::get('analyse/{analyse}/lesion', 'API\LesionController@listLesionsByAnalyse');

    Route::get('patient/all', 'API\RegisterController@listAllPatients');

    Route::get('analyse/all/{user}', 'API\AnalyseController@listAllAnalyses');
});
