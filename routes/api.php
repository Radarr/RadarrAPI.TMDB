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

Route::get("/mappings/get", "API\MappingsController@get");

Route::get("/mappings/add", "API\MappingsController@add");

Route::get("/mappings/vote", "API\MappingsController@vote");

Route::get("/imdb/top250", "API\IMDBController@top250");

Route::get("/imdb/popular", "API\IMDBController@popular");

Route::get("/imdb/list", "API\IMDBController@list");
