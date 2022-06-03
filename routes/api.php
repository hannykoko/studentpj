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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group(['prefix'=>'students'],function(){
    Route::get('/search','StudentController@search');
    Route::post('/register','StudentController@register');
    Route::get('/detail/{id}','StudentController@detail');
    Route::post('/update/{id}','StudentController@update');
    Route::delete('/delete/{id}','StudentController@asdf');
});


