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

Route::get('/movie/{id}', "API\MovieController@index");

Route::get('/collection/{id}', function ($id) {
    return \App\Collection::with(['movies'])->findOrFail($id);
});

Route::get('/collection/{id}/movies', function ($id) {
    return \App\Collection::with(['movies'])->findOrFail($id)->movies;
});

Route::get('/movies', function () {
    return \App\Movie::select()->filter()->orderBy('popularity', 'DESC')->paginate(10);
});
