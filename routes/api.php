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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Users Routes
Route::apiResources(['user' => 'API\UserController']);
Route::get('currentUser', 'API\UserController@current');
Route::get('email', 'API\UserController@uniqueEmail');
Route::get('experts', 'API\UserController@findExperts');

//Profile Routes 
Route::apiResources(['profile' => 'API\ProfileController']);
Route::put('profile', 'API\ProfileController@profile');
Route::put('photo', 'API\ProfileController@updatePhoto');
Route::put('theme', 'API\ProfileController@addTheme');

//POST ROUTES
Route::apiResources(['post' => 'API\PostController']);