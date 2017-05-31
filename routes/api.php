<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("/hello/{name?}", "API\TestJSONController@hello");

Route::get("/ping", "API\JSONController@ping");

Route::get("/discovery/upcoming", "API\DiscoverController@upcoming");

Route::get("/search/suggestions", "API\SearchController@suggestions");
