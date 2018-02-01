<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

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

Route::get("/ping", "API\JSONController@ping");

Route::get("/hello", "API\TestJSONController@hello");


Route::get("/discovery/upcoming", "API\DiscoverController@upcoming");

Route::get("/discovery/popular", "API\DiscoverController@popular");

Route::get("/discovery/recommendations", "API\DiscoverController@recommendations");

Route::post("/discovery/upcoming", "API\DiscoverController@upcoming");

Route::post("/discovery/popular", "API\DiscoverController@popular");

Route::post("/discovery/recommendations", "API\DiscoverController@recommendations");

Route::get("/search/suggestions", "API\SearchController@suggestions");

if (Config::get("app.mappings.enabled") === true)
{
    Route::get("/mappings/get", "API\MappingsController@get");

    Route::get("/mappings/add", "API\MappingsController@add");

    Route::get("/mappings/vote", "API\MappingsController@vote");

    Route::get("/mappings/latest", "API\MappingsController@latest");

    Route::get("/mappings/find", "API\MappingsController@find");
}

Route::get("/imdb/top250", "API\IMDBController@top250");

Route::get("/imdb/popular", "API\IMDBController@popular");

Route::get("/imdb/list", "API\IMDBController@user_list");

Route::get("/movie/{id}", function($id) {
    return \App\Movie::defaultWith()->findOrFail($id);
});

Route::post("/movie/refresh", "API\MovieController@refresh_movies");

Route::get("/collection/{id}", function($id) {
    return \App\Collection::with(["movies", "movies.similar"])->findOrFail($id);
});

Route::get("/collection/{id}/movies", function($id) {
    return \App\Collection::with(["movies", "movies.similar"])->findOrFail($id)->movies;
});

Route::get("/person/{id}/movies", "API\PersonController@person_movies");

//Route::get("/maintenance/activate", "DBMaintenanceController@activate");
