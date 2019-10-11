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

Route::get('/', function() {
	Log::info("Setfive Jokes API Version 1.0");
	return response()->json(["version" => "1.0"]);
});

Route::get('/load', ['uses' => 'JokeController@load']);
Route::get('/search/{term}', ['uses' => 'JokeController@search']);
Route::get('/joke/{id}', ['uses' => 'JokeController@view']);
Route::post('/rate', ['uses' => 'JokeController@rate']);
Route::post('/comment', ['uses' => 'JokeController@comment']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
