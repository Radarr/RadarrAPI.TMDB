<?php

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

Route::get('/', function () {
	$movies = \App\Movie::find([11,12]);
	//$movies = array();
    return view('welcome', ['movies' => $movies]);
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
